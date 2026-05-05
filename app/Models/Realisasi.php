<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model {
    protected $table    = 'realisasis';
    protected $fillable = ['indikator_id','user_id','bulan','tahun','nilai','target_snapshot','capaian','skor','keterangan','status','validated_by','validated_at'];
    protected $casts    = ['nilai'=>'float','target_snapshot'=>'float','capaian'=>'float','skor'=>'float','validated_at'=>'datetime'];

    public function indikator(){ return $this->belongsTo(Indikator::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function validator(){ return $this->belongsTo(User::class,'validated_by'); }

    public function getStatusLabelAttribute(): string {
        return match($this->status) { 'approved'=>'Disetujui','submitted'=>'Menunggu','rejected'=>'Ditolak',default=>'Draft' };
    }
    public function getStatusBadgeAttribute(): string {
        return match($this->status) { 'approved'=>'badge-ok','submitted'=>'badge-wait','rejected'=>'badge-bad',default=>'badge-gray' };
    }
    public function getCapaianWarnAttribute(): string {
        if ($this->capaian >= 100) return 'text-ok';
        if ($this->capaian >= 80)  return 'text-warn';
        return 'text-bad';
    }
}