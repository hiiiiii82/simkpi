<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model {
    protected $table    = 'kategoris';
    protected $fillable = ['nama','kode','warna','deskripsi','is_active'];
    protected $casts    = ['is_active'=>'boolean'];
    public function indikators() { return $this->hasMany(Indikator::class); }
}