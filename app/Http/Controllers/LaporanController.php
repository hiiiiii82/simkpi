<?php
namespace App\Http\Controllers;
use App\Models\{Kategori, Realisasi, Evaluasi, Indikator};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller {
    public function index(Request $request) {
        $tahun = (int)($request->tahun ?? 2026);
        $bulan = (int)($request->bulan ?? 1);

        $rekap = collect(range(1,12))->map(function($b) use ($tahun) {
            $skor = Realisasi::where('bulan',$b)->where('tahun',$tahun)->where('status','approved')->sum('skor');
            $bobot= Indikator::sum('bobot');
            $prop = $bobot > 0 ? round($skor/$bobot*100,2) : 0;
            $cap  = Realisasi::where('bulan',$b)->where('tahun',$tahun)->where('status','approved')->avg('capaian') ?? 0;
            $ev   = Evaluasi::where('bulan',$b)->where('tahun',$tahun)->first();
            return ['bulan'=>$b,'nama'=>['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$b],'skor'=>round($skor,2),'proporsional'=>$prop,'capaian'=>round($cap,1),'predikat'=>$ev?->predikat ?? ($skor>0 ? Evaluasi::predikatDari($prop) : '-'),'jumlah'=>Realisasi::where('bulan',$b)->where('tahun',$tahun)->where('status','approved')->count()];
        });

        $perKategori = Kategori::where('is_active',true)->get()->map(function($k) use ($tahun) {
            $ids = $k->indikators->pluck('id');
            return ['nama'=>$k->nama,'warna'=>$k->warna,'skor'=>round(Realisasi::whereIn('indikator_id',$ids)->where('tahun',$tahun)->where('status','approved')->sum('skor'),2),'capaian'=>round(Realisasi::whereIn('indikator_id',$ids)->where('tahun',$tahun)->where('status','approved')->avg('capaian') ?? 0,1)];
        });

        return view('laporan.index', compact('rekap','perKategori','tahun','bulan'));
    }

    public function pdf(Request $request) {
        $bulan = (int)($request->bulan ?? 1); $tahun = (int)($request->tahun ?? 2026);
        $realisasis  = Realisasi::with('indikator.kategori')->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->orderBy('indikator_id')->get();
        $perKategori = $realisasis->groupBy(fn($r) => $r->indikator->kategori->nama);
        $totalSkor   = round($realisasis->sum('skor'),2);
        $bobot       = Indikator::sum('bobot');
        $proporsional= $bobot > 0 ? round($totalSkor/$bobot*100,2) : 0;
        $predikat    = Evaluasi::predikatDari($proporsional);
        $namaBulan   = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan];
        $tglCetak    = now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i');
        $dicetak     = Auth::user()->name;

        $pdf = Pdf::loadView('laporan.pdf', compact('realisasis','perKategori','totalSkor','proporsional','predikat','bulan','tahun','namaBulan','tglCetak','dicetak'))->setPaper('a4','landscape');
        return $pdf->download("KPI_PLN_UP3_Surakarta_{$namaBulan}_{$tahun}.pdf");
    }

    public function excel(Request $request) {
        $bulan = (int)($request->bulan ?? 1); $tahun = (int)($request->tahun ?? 2026);
        $realisasis = Realisasi::with('indikator.kategori')->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->orderBy('indikator_id')->get();
        $namaBulan  = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan];

        $headers = ['Content-Type'=>'text/csv; charset=UTF-8','Content-Disposition'=>"attachment; filename=KPI_PLN_{$namaBulan}_{$tahun}.csv"];
        $cb = function() use ($realisasis,$namaBulan,$tahun) {
            $f = fopen('php://output','w');
            fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($f,['LAPORAN KPI PLN UP3 SURAKARTA'],';');
            fputcsv($f,["Periode: {$namaBulan} {$tahun}"],';');
            fputcsv($f,['Dicetak: '.now()->format('d/m/Y H:i')],';');
            fputcsv($f,[],';');
            fputcsv($f,['No','Kode','Indikator KPI','Kategori','Satuan','Target','Realisasi','Capaian (%)','Bobot (%)','Skor'],';');
            foreach ($realisasis as $i=>$r) {
                fputcsv($f,[$i+1,$r->indikator->kode,$r->indikator->nama,$r->indikator->kategori->nama,$r->indikator->satuan,$r->target_snapshot,$r->nilai,$r->capaian,$r->indikator->bobot,$r->skor],';');
            }
            fputcsv($f,[],';');
            fputcsv($f,['','','','','','','','','TOTAL SKOR',round($realisasis->sum('skor'),2)],';');
            fclose($f);
        };
        return response()->stream($cb,200,$headers);
    }
}