<?php
namespace App\Http\Controllers;
use App\Models\{Kategori, Indikator, Realisasi, Evaluasi, Ulp, RealisasiUlp};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function index() {
        $bulan = now()->month;
        $tahun = now()->year;

        // Pakai tahun 2026 jika data ada di sana
        if (!Realisasi::where('tahun',$tahun)->exists() && Realisasi::where('tahun',2026)->exists()) {
            $tahun = 2026; $bulan = 1;
        }

        $totalSkor  = Realisasi::where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->sum('skor');
        $totalBobot = Indikator::sum('bobot');
        $proporsional = $totalBobot > 0 ? round($totalSkor / $totalBobot * 100, 2) : 0;
        $avgCapaian = Realisasi::where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->avg('capaian') ?? 0;
        $predikat   = Evaluasi::predikatDari($proporsional);

        $totalInd   = Indikator::where('is_active',true)->count();
        $sudahInput = Realisasi::where('bulan',$bulan)->where('tahun',$tahun)->count();
        $menunggu   = Realisasi::where('status','submitted')->count();

        // Per kategori
        $kategoris = Kategori::where('is_active',true)->with('indikators')->get();
        $perKategori = $kategoris->map(function($k) use ($bulan,$tahun) {
            $ids   = $k->indikators->pluck('id');
            $skor  = Realisasi::whereIn('indikator_id',$ids)->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->sum('skor');
            $bobot = $k->indikators->sum('bobot');
            $cap   = Realisasi::whereIn('indikator_id',$ids)->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->avg('capaian') ?? 0;
            return ['nama'=>$k->nama,'kode'=>$k->kode,'warna'=>$k->warna,'skor'=>round($skor,2),'bobot'=>$bobot,'capaian'=>round($cap,1)];
        });

        // Tren
        $tren = [];
        for ($i = 5; $i >= 0; $i--) {
            $tgl = now()->subMonths($i);
            $b = $tgl->month; $t = $tgl->year;
            if (!Realisasi::where('tahun',$t)->exists() && $t < 2026) { $t = 2026; $b = 1; }
            $s = Realisasi::where('bulan',$b)->where('tahun',$t)->where('status','approved')->sum('skor');
            $tren[] = ['label'=>$tgl->format('M Y'),'skor'=>round($s,2)];
        }

        // KPI kritis & terbaik
        $kritis  = Realisasi::with('indikator.kategori')->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->where('capaian','<',80)->orderBy('capaian')->limit(5)->get();
        $terbaik = Realisasi::with('indikator.kategori')->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->where('capaian','>=',100)->orderByDesc('capaian')->limit(5)->get();

        // Rekap ULP
        $ulps = Ulp::where('is_active',true)->get();
        $rekapUlp = $ulps->map(function($ulp) use ($bulan,$tahun) {
            $reals = RealisasiUlp::where('ulp_id',$ulp->id)->where('bulan',$bulan)->where('tahun',$tahun)->get();
            return ['nama'=>$ulp->nama,'kode'=>$ulp->kode,'avg_capaian'=>round($reals->avg('capaian') ?? 0,1),'jumlah'=>$reals->count()];
        });

        return view('dashboard.index', compact('totalSkor','proporsional','avgCapaian','predikat','perKategori','tren','kritis','terbaik','totalInd','sudahInput','menunggu','bulan','tahun','rekapUlp'));
    }
}