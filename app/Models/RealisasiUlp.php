<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RealisasiUlp extends Model {
    protected $table    = 'realisasi_ulps';
    protected $fillable = ['indikator_id','ulp_id','user_id','bulan','tahun','nilai','target_snapshot','capaian','keterangan'];
    protected $casts    = ['nilai'=>'float','target_snapshot'=>'float','capaian'=>'float'];

    public function indikator(){ return $this->belongsTo(Indikator::class); }
    public function ulp()      { return $this->belongsTo(Ulp::class); }
    public function user()     { return $this->belongsTo(User::class); }

    public function getCapaianWarnAttribute(): string {
        if ($this->capaian >= 100) return 'text-ok';
        if ($this->capaian >= 80)  return 'text-warn';
        return 'text-bad';
    }
}