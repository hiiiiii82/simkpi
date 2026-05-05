<?php
namespace App\Http\Controllers;

use App\Models\{Indikator, Realisasi, Kategori};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RealisasiController extends Controller
{
    /* ── Tampilkan form input ──────────────────────────────── */
    public function index(Request $request)
    {
        $bulan = (int)($request->bulan ?? 1);
        $tahun = (int)($request->tahun ?? 2026);

        $indikators = Indikator::with([
            'kategori',
            'realisasis' => fn($q) => $q->where('bulan', $bulan)->where('tahun', $tahun)
        ])
        ->where('is_active', true)
        ->orderBy('kategori_id')
        ->orderBy('kode')
        ->get();

        return view('kpi.input', compact('indikators', 'bulan', 'tahun'));
    }

    /* ── Simpan data realisasi ─────────────────────────────── */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'indikator_id' => 'required|integer|exists:indikators,id',
            'bulan'        => 'required|integer|between:1,12',
            'tahun'        => 'required|integer|min:2020|max:2099',
            'nilai'        => 'required|numeric',
            'keterangan'   => 'nullable|string|max:500',
        ], [
            'indikator_id.required' => 'Indikator harus dipilih.',
            'indikator_id.exists'   => 'Indikator tidak ditemukan.',
            'nilai.required'        => 'Nilai realisasi wajib diisi.',
            'nilai.numeric'         => 'Nilai realisasi harus berupa angka.',
        ]);

        try {
            DB::beginTransaction();

            $ind     = Indikator::findOrFail($validated['indikator_id']);
            $nilai   = (float) $validated['nilai'];
            $capaian = $ind->hitungCapaian($nilai);
            $skor    = $ind->hitungSkor($capaian);

            // Cek apakah sudah ada data untuk periode ini
            $existing = Realisasi::where('indikator_id', $ind->id)
                ->where('bulan', $validated['bulan'])
                ->where('tahun', $validated['tahun'])
                ->first();

            if ($existing) {
                // Jika sudah approved, tidak bisa diubah
                if ($existing->status === 'approved') {
                    return back()->with('error', 'Data sudah disetujui dan tidak dapat diubah.');
                }
                // Update data yang ada
                $existing->update([
                    'user_id'         => Auth::id(),
                    'nilai'           => $nilai,
                    'target_snapshot' => $ind->target,
                    'capaian'         => $capaian,
                    'skor'            => $skor,
                    'keterangan'      => $validated['keterangan'] ?? null,
                    'status'          => 'submitted',
                    'validated_by'    => null,
                    'validated_at'    => null,
                ]);
                $realisasi = $existing;
            } else {
                // Buat data baru
                $realisasi = Realisasi::create([
                    'indikator_id'    => $ind->id,
                    'user_id'         => Auth::id(),
                    'bulan'           => (int) $validated['bulan'],
                    'tahun'           => (int) $validated['tahun'],
                    'nilai'           => $nilai,
                    'target_snapshot' => $ind->target,
                    'capaian'         => $capaian,
                    'skor'            => $skor,
                    'keterangan'      => $validated['keterangan'] ?? null,
                    'status'          => 'submitted',
                ]);
            }

            DB::commit();

            Log::info('Realisasi disimpan', [
                'id'           => $realisasi->id,
                'indikator'    => $ind->kode,
                'nilai'        => $nilai,
                'capaian'      => $capaian,
                'user'         => Auth::id(),
            ]);

            return back()->with('success', "Data KPI [{$ind->kode}] berhasil disimpan. Capaian: " . number_format($capaian, 1) . "%. Menunggu validasi admin.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan realisasi: ' . $e->getMessage(), [
                'request' => $request->all(),
                'user'    => Auth::id(),
            ]);
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /* ── Hapus data (draft/submitted saja) ────────────────── */
    public function destroy($id)
    {
        $r = Realisasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['draft', 'submitted'])
            ->first();

        if (!$r) {
            return back()->with('error', 'Data tidak ditemukan atau tidak dapat dihapus.');
        }

        $r->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    /* ── Halaman validasi (admin only) ────────────────────── */
    public function validasiIndex()
    {
        $realisasis = Realisasi::with(['indikator.kategori', 'user'])
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kpi.validasi', compact('realisasis'));
    }

    /* ── Setujui data ─────────────────────────────────────── */
    public function approve($id)
    {
        $r = Realisasi::findOrFail($id);
        $r->update([
            'status'       => 'approved',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);
        return back()->with('success', 'Data KPI [' . $r->indikator->kode . '] berhasil disetujui.');
    }

    /* ── Tolak data ───────────────────────────────────────── */
    public function reject($id)
    {
        $r = Realisasi::findOrFail($id);
        $r->update([
            'status'       => 'rejected',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);
        return back()->with('success', 'Data KPI [' . $r->indikator->kode . '] ditolak.');
    }
}