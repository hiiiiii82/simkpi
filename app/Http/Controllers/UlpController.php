<?php
namespace App\Http\Controllers;
use App\Models\{Ulp, Indikator, IndikatorUlp, RealisasiUlp};
use Illuminate\Http\Request;

class UlpController extends Controller {
    public function index() {
        $ulps = Ulp::where('is_active',true)->get();
        $indikators = Indikator::with('kategori')->where('is_active',true)->orderBy('kategori_id')->orderBy('kode')->get();
        return view('ulp.index', compact('ulps','indikators'));
    }

    public function show(Request $request, $id) {
        $ulp    = Ulp::findOrFail($id);
        $bulan  = (int)($request->bulan ?? 1);
        $tahun  = (int)($request->tahun ?? 2026);
        $ulps   = Ulp::where('is_active',true)->get();

        $realisasis = RealisasiUlp::with('indikator.kategori')
            ->where('ulp_id',$id)->where('bulan',$bulan)->where('tahun',$tahun)
            ->orderBy('indikator_id')->get();

        $perKategori = $realisasis->groupBy(fn($r) => $r->indikator->kategori->nama ?? 'Lainnya');
        $avgCapaian  = round($realisasis->avg('capaian') ?? 0, 1);

        return view('ulp.show', compact('ulp','ulps','realisasis','perKategori','avgCapaian','bulan','tahun'));
    }

    public function store(Request $request) {
        $request->validate(['nama'=>'required|string|max:100','kode'=>'required|string|max:10']);
        Ulp::create($request->only('nama','kode') + ['is_active'=>true]);
        return back()->with('success','ULP berhasil ditambahkan.');
    }
    public function update(Request $request, $id) {
        Ulp::findOrFail($id)->update($request->only('nama','kode','is_active'));
        return back()->with('success','ULP berhasil diperbarui.');
    }
    public function destroy($id) { Ulp::findOrFail($id)->delete(); return back()->with('success','ULP dihapus.'); }
}