<?php
// ── User ────────────────────────────────────────────────────────────
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $fillable = ['name','email','password','role','nip','jabatan','unit_kerja','is_active'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['is_active'=>'boolean','password'=>'hashed'];

    public function isAdmin()     { return $this->role === 'admin'; }
    public function canValidate() { return in_array($this->role,['admin','manajer']); }
    public function canReport()   { return in_array($this->role,['admin','manajer','supervisor']); }

    public function getRoleLabelAttribute(): string {
        return match($this->role) { 'admin'=>'Administrator','manajer'=>'Manajer','supervisor'=>'Supervisor',default=>'Pegawai' };
    }
    public function getInisialAttribute(): string {
        $w = explode(' ', trim($this->name));
        return strtoupper(substr($w[0],0,1).(isset($w[1]) ? substr($w[1],0,1) : ''));
    }
}