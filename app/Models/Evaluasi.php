<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model {
    protected $table    = 'evaluasis';
    protected $fillable = ['bulan','tahun','total_skor','total_proporsional','predikat','catatan','dievaluasi_oleh','status'];
    protected $casts    = ['total_skor'=>'float','total_proporsional'=>'float'];

    public function evaluator(){ return $this->belongsTo(User::class,'dievaluasi_oleh'); }

    public static function predikatDari(float $skor): string {
        return match(true) { $skor>=90=>'Sangat Baik',$skor>=80=>'Baik',$skor>=70=>'Cukup',$skor>=60=>'Kurang',default=>'Sangat Kurang' };
    }
    public static function kelasPredikat(string $p): string {
        return match($p) { 'Sangat Baik'=>'pred-sb','Baik'=>'pred-b','Cukup'=>'pred-c','Kurang'=>'pred-k',default=>'pred-sk' };
    }
    public function getPredKelasAttribute(): string { return self::kelasPredikat($this->predikat ?? ''); }
}