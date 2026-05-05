<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model {
    protected $table    = 'indikators';
    protected $fillable = ['kategori_id','nama','kode','satuan','target','bobot','arah','periode','is_active'];
    protected $casts    = ['is_active'=>'boolean','target'=>'float','bobot'=>'float'];

    public function kategori()     { return $this->belongsTo(Kategori::class); }
    public function realisasis()   { return $this->hasMany(Realisasi::class); }
    public function realisasiUlps(){ return $this->hasMany(RealisasiUlp::class); }
    public function indikatorUlps(){ return $this->hasMany(IndikatorUlp::class); }

    public function targetUlp(int $ulpId): float {
        $iu = IndikatorUlp::where('indikator_id',$this->id)->where('ulp_id',$ulpId)->first();
        return $iu ? (float)$iu->target : (float)$this->target;
    }

    public function hitungCapaian(float $nilai, float $target = null): float {
        $t = $target ?? (float)$this->target;
        if ($this->arah === 'turun') {
            if ($t <= 0) return $nilai <= 0 ? 110.0 : 0.0;
            return min(round(($t / max(abs($nilai),0.0001)) * 100, 2), 150.0);
        }
        if ($t == 0) return $nilai > 0 ? 110.0 : 100.0;
        return min(round(($nilai / $t) * 100, 2), 150.0);
    }

    public function hitungSkor(float $capaian): float {
        return round(($capaian / 100) * (float)$this->bobot, 2);
    }
}