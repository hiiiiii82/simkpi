<?php
namespace App\Http\Controllers;

use App\Models\{Indikator, Realisasi, RealisasiUlp, Kategori, IndikatorUlp};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RealisasiController extends Controller
{
    /* ── Tampilkan form input (Admin ULP – hanya ULP sendiri) ── */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $bulan = (int)($request->bulan ?? now()->month);
        $tahun = (int)($request->tahun ?? now()->year);

        // Paksa ke ULP milik user jika admin_ulp
        if ($user->isAdminUlp()) {
            if (!$user->ulp_id) {
                return redirect()->route('dashboard')
                    ->with('error', 'Akun Anda belum terhubung ke ULP. Hubungi Admin UP3.');
            }
            $ulp = $user->ulp;

            // Ambil indikator yang punya target ULP ini, atau semua jika belum ada target khusus
            $indikatorIds = IndikatorUlp::where('ulp_id', $ulp->id)->pluck('indikator_id');
            $indikators = Indikator::with([
                'kategori',
                'realisasiUlps' => fn($q) => $q->where('ulp_id', $ulp->id)
                                               ->where('bulan', $bulan)
                                               ->where('tahun', $tahun),
                'indikatorUlps' => fn($q) => $q->where('ulp_id', $ulp->id),
            ])
            ->where('is_active', true)
            ->orderBy('kategori_id')->orderBy('kode')
            ->get();

            return view('kpi.input_ulp', compact('indikators', 'bulan', 'tahun', 'ulp'));
        }

        // admin_up3 – input UP3 gabungan
        $indikators = Indikator::with([
            'kategori',
            'realisasis' => fn($q) => $q->where('bulan', $bulan)->where('tahun', $tahun)
        ])
        ->where('is_active', true)
        ->orderBy('kategori_id')->orderBy('kode')
        ->get();

        return view('kpi.input', compact('indikators', 'bulan', 'tahun'));
    }

    /* ── Simpan realisasi ULP ──────────────────────────────────── */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'indikator_id' => 'required|integer|exists:indikators,id',
            'bulan'        => 'required|integer|between:1,12',
            'tahun'        => 'required|integer|min:2020|max:2099',
            'nilai'        => 'required|numeric',
            'keterangan'   => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            $ind   = Indikator::findOrFail($validated['indikator_id']);
            $nilai = (float) $validated['nilai'];

            if ($user->isAdminUlp()) {
                // Simpan ke realisasi_ulps
                $ulpId = $user->ulp_id;
                $indUlp = IndikatorUlp::where('indikator_id', $ind->id)->where('ulp_id', $ulpId)->first();
                $target = $indUlp ? (float)$indUlp->target : (float)$ind->target;
                $capaian = $ind->hitungCapaian($nilai);

                $existing = RealisasiUlp::where('indikator_id', $ind->id)
                    ->where('ulp_id', $ulpId)
                    ->where('bulan', $validated['bulan'])
                    ->where('tahun', $validated['tahun'])
                    ->first();

                if ($existing) {
                    $existing->update([
                        'user_id'         => $user->id,
                        'nilai'           => $nilai,
                        'target_snapshot' => $target,
                        'capaian'         => $capaian,
                        'keterangan'      => $validated['keterangan'] ?? null,
                    ]);
                } else {
                    RealisasiUlp::create([
                        'indikator_id'    => $ind->id,
                        'ulp_id'          => $ulpId,
                        'user_id'         => $user->id,
                        'bulan'           => (int)$validated['bulan'],
                        'tahun'           => (int)$validated['tahun'],
                        'nilai'           => $nilai,
                        'target_snapshot' => $target,
                        'capaian'         => $capaian,
                        'keterangan'      => $validated['keterangan'] ?? null,
                    ]);
                }

                DB::commit();
                return back()->with('success', "Data KPI [{$ind->kode}] ULP {$user->ulp->nama} berhasil disimpan. Capaian: " . number_format($capaian, 1) . "%");
            }

            // admin_up3 – simpan ke realisasis (UP3 gabungan)
            $capaian = $ind->hitungCapaian($nilai);
            $skor    = $ind->hitungSkor($capaian);

            $existing = Realisasi::where('indikator_id', $ind->id)
                ->where('bulan', $validated['bulan'])
                ->where('tahun', $validated['tahun'])
                ->first();

            if ($existing) {
                if ($existing->status === 'approved') {
                    return back()->with('error', 'Data sudah disetujui dan tidak dapat diubah.');
                }
                $existing->update([
                    'user_id'         => $user->id,
                    'nilai'           => $nilai,
                    'target_snapshot' => $ind->target,
                    'capaian'         => $capaian,
                    'skor'            => $skor,
                    'keterangan'      => $validated['keterangan'] ?? null,
                    'status'          => 'submitted',
                    'validated_by'    => null,
                    'validated_at'    => null,
                ]);
            } else {
                Realisasi::create([
                    'indikator_id'    => $ind->id,
                    'user_id'         => $user->id,
                    'bulan'           => (int)$validated['bulan'],
                    'tahun'           => (int)$validated['tahun'],
                    'nilai'           => $nilai,
                    'target_snapshot' => $ind->target,
                    'capaian'         => $capaian,
                    'skor'            => $skor,
                    'keterangan'      => $validated['keterangan'] ?? null,
                    'status'          => 'submitted',
                ]);
            }

            DB::commit();
            return back()->with('success', "Data KPI [{$ind->kode}] berhasil disimpan. Capaian: " . number_format($capaian, 1) . "%. Menunggu validasi.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan realisasi: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /* ── Update realisasi ──────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'nilai'      => 'required|numeric',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            if ($user->isAdminUlp()) {
                // Hanya boleh edit data ULP sendiri
                $r = RealisasiUlp::where('id', $id)
                    ->where('ulp_id', $user->ulp_id)
                    ->firstOrFail();

                $ind     = $r->indikator;
                $indUlp  = IndikatorUlp::where('indikator_id', $ind->id)->where('ulp_id', $user->ulp_id)->first();
                $target  = $indUlp ? (float)$indUlp->target : (float)$ind->target;
                $nilai   = (float)$request->nilai;
                $capaian = $ind->hitungCapaian($nilai, $target);

                $r->update([
                    'user_id'         => $user->id,
                    'nilai'           => $nilai,
                    'target_snapshot' => $target,
                    'capaian'         => $capaian,
                    'keterangan'      => $request->keterangan,
                ]);

                DB::commit();
                return back()->with('success', "KPI [{$ind->kode}] berhasil diperbarui. Capaian: " . number_format($capaian, 1) . "%");
            }

            // admin_up3 update realisasis
            $r = Realisasi::findOrFail($id);

            if ($r->status === 'approved') {
                DB::rollBack();
                return back()->with('error', 'Data yang sudah disetujui tidak dapat diubah.');
            }

            $ind     = $r->indikator;
            $nilai   = (float)$request->nilai;
            $capaian = $ind->hitungCapaian($nilai);
            $skor    = $ind->hitungSkor($capaian);

            $r->update([
                'user_id'         => $user->id,
                'nilai'           => $nilai,
                'target_snapshot' => $ind->target,
                'capaian'         => $capaian,
                'skor'            => $skor,
                'keterangan'      => $request->keterangan,
                'status'          => 'submitted',
                'validated_by'    => null,
                'validated_at'    => null,
            ]);

            DB::commit();
            return back()->with('success', "KPI [{$ind->kode}] berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update realisasi: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /* ── Hapus data ────────────────────────────────────────────── */
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->isAdminUlp()) {
            $r = RealisasiUlp::where('id', $id)
                ->where('ulp_id', $user->ulp_id)
                ->first();
            if (!$r) return back()->with('error', 'Data tidak ditemukan.');
            $r->delete();
        } else {
            $r = Realisasi::where('id', $id)
                ->whereIn('status', ['draft','submitted'])
                ->first();
            if (!$r) return back()->with('error', 'Data tidak ditemukan atau tidak dapat dihapus.');
            $r->delete();
        }

        return back()->with('success', 'Data berhasil dihapus.');
    }

    /* ── Halaman validasi (admin_up3 only) ─────────────────────── */
    public function validasiIndex()
    {
        $realisasis = Realisasi::with(['indikator.kategori', 'user'])
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kpi.validasi', compact('realisasis'));
    }

    public function approve($id)
    {
        $r = Realisasi::findOrFail($id);
        $r->update(['status'=>'approved','validated_by'=>Auth::id(),'validated_at'=>now()]);
        return back()->with('success', 'Data KPI [' . $r->indikator->kode . '] berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['alasan' => 'nullable|string|max:300']);
        $r = Realisasi::findOrFail($id);
        $r->update([
            'status'       => 'rejected',
            'keterangan'   => $request->alasan ?? $r->keterangan,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);
        return back()->with('success', 'Data KPI [' . $r->indikator->kode . '] ditolak.');
    }
}
