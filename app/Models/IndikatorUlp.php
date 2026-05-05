<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IndikatorUlp extends Model {
    protected $table    = 'indikator_ulps';
    protected $fillable = ['indikator_id','ulp_id','target','is_active'];
    protected $casts    = ['target'=>'float','is_active'=>'boolean'];
    public function indikator(){ return $this->belongsTo(Indikator::class); }
    public function ulp()      { return $this->belongsTo(Ulp::class); }
}