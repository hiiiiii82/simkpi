<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ulp extends Model {
    protected $table    = 'ulps';
    protected $fillable = ['nama','kode','is_active'];
    protected $casts    = ['is_active'=>'boolean'];
    public function realisasis() { return $this->hasMany(RealisasiUlp::class); }
    public function indikatorUlps() { return $this->hasMany(IndikatorUlp::class); }
}