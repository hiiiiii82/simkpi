<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name','email','password','role',
        'ulp_id','nip','jabatan','unit_kerja','is_active'
    ];
    protected $hidden = ['password','remember_token'];
    protected $casts  = ['is_active'=>'boolean','password'=>'hashed'];

    public function ulp() {
        return $this->belongsTo(Ulp::class);
    }

    public function isAdminUp3()  { return $this->role === 'admin_up3'; }
    public function isAdminUlp()  { return $this->role === 'admin_ulp'; }
    public function isAdmin()     { return in_array($this->role, ['admin_up3','admin_ulp']); }
    public function canValidate() { return $this->role === 'admin_up3'; }
    public function canReport()   { return in_array($this->role, ['admin_up3','admin_ulp']); }

    public function getRoleLabelAttribute(): string {
        return match($this->role) {
            'admin_up3' => 'Admin UP3',
            'admin_ulp' => 'Admin ULP' . ($this->ulp ? ' – ' . $this->ulp->nama : ''),
            default     => 'Pengguna',
        };
    }

    public function getInisialAttribute(): string {
        $w = explode(' ', trim($this->name));
        return strtoupper(substr($w[0],0,1).(isset($w[1]) ? substr($w[1],0,1) : ''));
    }
}