<?php
namespace App\Http\Controllers;
use App\Models\{Evaluasi, Realisasi, Indikator};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiController extends Controller {
    public function index(Request $request) {
        $tahun   = (int)($request->tahun ?? 2026);
        $daftar  = Evaluasi::where('tahun',$tahun)->orderBy('bulan')->get();
        $chartSkor = collect(range(1,12))->map(fn($b) => Evaluasi::where('bulan',$b)->where('tahun',$tahun)->first()?->total_proporsional ?? 0);
        $avgSkor = $daftar->avg('total_proporsional') ?? 0;
        return view('evaluasi.index', compact('daftar','tahun','chartSkor','avgSkor'));
    }

    public function generate(Request $request) {
        $request->validate(['bulan'=>'required|integer|between:1,12','tahun'=>'required|integer|min:2020']);
        $b = (int)$request->bulan; $t = (int)$request->tahun;
        $skor = round(Realisasi::where('bulan',$b)->where('tahun',$t)->where('status','approved')->sum('skor'),2);
        $bobot= Indikator::sum('bobot');
        $prop = $bobot > 0 ? round($skor/$bobot*100,2) : 0;
        Evaluasi::updateOrCreate(['bulan'=>$b,'tahun'=>$t],['total_skor'=>$skor,'total_proporsional'=>$prop,'predikat'=>Evaluasi::predikatDari($prop),'dievaluasi_oleh'=>Auth::id(),'status'=>'selesai']);
        return redirect()->route('evaluasi.index')->with('success',"Evaluasi {$b}/{$t} di-generate. Skor Proporsional: {$prop}");
    }

    public function detail($bulan, $tahun) {
        $evaluasi   = Evaluasi::where('bulan',$bulan)->where('tahun',$tahun)->firstOrFail();
        $realisasis = Realisasi::with('indikator.kategori')->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->orderBy('indikator_id')->get();
        $perKategori= $realisasis->groupBy(fn($r) => $r->indikator->kategori->nama)->map(fn($grp) => ['skor'=>round($grp->sum('skor'),2),'capaian'=>round($grp->avg('capaian'),1),'warna'=>$grp->first()->indikator->kategori->warna]);
        return view('evaluasi.detail', compact('evaluasi','realisasis','perKategori','bulan','tahun'));
    }
}