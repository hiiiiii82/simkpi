<?php
namespace App\Http\Controllers;
use App\Models\{Kategori, Realisasi, Ulp, RealisasiUlp};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller {
    public function index(Request $request) {
        $user  = Auth::user();
        $bulan = (int)($request->bulan ?? 1);
        $tahun = (int)($request->tahun ?? 2026);

        // BUG 3 FIX: admin_ulp hanya boleh lihat ULP-nya sendiri
        $ulps = $user->isAdminUlp()
            ? Ulp::where('id', $user->ulp_id)->where('is_active', true)->get()
            : Ulp::where('is_active', true)->get();

        return view('monitoring.index', compact('bulan','tahun','ulps'));
    }

    public function data(Request $request) {
        $user  = Auth::user();
        $bulan = (int)($request->bulan ?? 1);
        $tahun = (int)($request->tahun ?? 2026);

        // BUG 3 FIX: admin_ulp paksa pakai ulp_id miliknya sendiri, abaikan request
        $ulpId = $user->isAdminUlp() ? $user->ulp_id : $request->ulp_id;

        if ($ulpId) {
            // Data per ULP
            $hasil = Kategori::where('is_active',true)->with(['indikators'=>fn($q)=>$q->where('is_active',true)->orderBy('kode')])->orderBy('kode')->get()->map(function($kat) use ($bulan,$tahun,$ulpId) {
                $inds = $kat->indikators->map(function($ind) use ($bulan,$tahun,$ulpId) {
                    $r = RealisasiUlp::where('indikator_id',$ind->id)->where('ulp_id',$ulpId)->where('bulan',$bulan)->where('tahun',$tahun)->first();
                    return ['id'=>$ind->id,'kode'=>$ind->kode,'nama'=>$ind->nama,'satuan'=>$ind->satuan,'target'=>$ind->targetUlp((int)$ulpId),'bobot'=>$ind->bobot,'arah'=>$ind->arah,'nilai'=>$r?->nilai,'capaian'=>$r?->capaian,'status'=>$r ? 'ada' : 'belum'];
                });
                return ['nama'=>$kat->nama,'kode'=>$kat->kode,'warna'=>$kat->warna,'avg_capaian'=>round($inds->whereNotNull('capaian')->avg('capaian') ?? 0,1),'indikators'=>$inds->values()];
            });
        } else {
            // Data UP3
            $hasil = Kategori::where('is_active',true)->with(['indikators'=>fn($q)=>$q->where('is_active',true)->orderBy('kode')])->orderBy('kode')->get()->map(function($kat) use ($bulan,$tahun) {
                $inds = $kat->indikators->map(function($ind) use ($bulan,$tahun) {
                    $r = Realisasi::where('indikator_id',$ind->id)->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')->first();
                    return ['id'=>$ind->id,'kode'=>$ind->kode,'nama'=>$ind->nama,'satuan'=>$ind->satuan,'target'=>$ind->target,'bobot'=>$ind->bobot,'arah'=>$ind->arah,'nilai'=>$r?->nilai,'capaian'=>$r?->capaian,'skor'=>$r?->skor,'status'=>$r?->status ?? 'belum'];
                });
                return ['nama'=>$kat->nama,'kode'=>$kat->kode,'warna'=>$kat->warna,'total_skor'=>round($inds->sum('skor'),2),'avg_capaian'=>round($inds->whereNotNull('capaian')->avg('capaian') ?? 0,1),'indikators'=>$inds->values()];
            });
        }

        return response()->json(['ok'=>true,'timestamp'=>now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s'),'bulan'=>$bulan,'tahun'=>$tahun,'ulp_id'=>$ulpId,'data'=>$hasil]);
    }
}