<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Ulp;
use App\Models\User;

class UlpAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Hapus data lama agar tidak konflik sama sekali
        User::whereIn('role', ['admin_up3','admin_ulp','admin','manajer','supervisor','pegawai'])->delete();
        Ulp::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Buat ULP
        $ulpData = [
            ['kode' => 'MNH', 'nama' => 'Manahan'],
            ['kode' => 'KTS', 'nama' => 'Kartasuro'],
            ['kode' => 'PLR', 'nama' => 'Palur'],
            ['kode' => 'SBL', 'nama' => 'Sumberlawang'],
            ['kode' => 'SKT', 'nama' => 'Surakarta Kota'],
            ['kode' => 'SRG', 'nama' => 'Sragen'],
        ];

        foreach ($ulpData as $u) {
            Ulp::create(['kode' => $u['kode'], 'nama' => $u['nama'], 'is_active' => true]);
        }

        // Admin UP3
        User::create([
            'name'       => 'Admin UP3',
            'email'      => 'admin.up3@pln.co.id',
            'password'   => Hash::make('password'),
            'role'       => 'admin_up3',
            'nip'        => null,
            'jabatan'    => 'Administrator UP3',
            'unit_kerja' => 'UP3 Surakarta',
            'ulp_id'     => null,
            'is_active'  => true,
        ]);

        // Admin tiap ULP
        $adminUlp = [
            ['nama' => 'Admin Manahan',        'email' => 'admin.manahan@pln.co.id',       'kode' => 'MNH'],
            ['nama' => 'Admin Kartasuro',      'email' => 'admin.kartasuro@pln.co.id',     'kode' => 'KTS'],
            ['nama' => 'Admin Palur',          'email' => 'admin.palur@pln.co.id',         'kode' => 'PLR'],
            ['nama' => 'Admin Sumberlawang',   'email' => 'admin.sumberlawang@pln.co.id',  'kode' => 'SBL'],
            ['nama' => 'Admin Surakarta Kota', 'email' => 'admin.surakartakota@pln.co.id', 'kode' => 'SKT'],
            ['nama' => 'Admin Sragen',         'email' => 'admin.sragen@pln.co.id',        'kode' => 'SRG'],
        ];

        foreach ($adminUlp as $u) {
            $ulp = Ulp::where('kode', $u['kode'])->first();
            User::create([
                'name'       => $u['nama'],
                'email'      => $u['email'],
                'password'   => Hash::make('password'),
                'role'       => 'admin_ulp',
                'nip'        => null,
                'jabatan'    => 'Admin ULP',
                'unit_kerja' => $u['nama'],
                'ulp_id'     => $ulp->id,
                'is_active'  => true,
            ]);
        }

        $this->command->info('Seeder selesai! 1 Admin UP3 + 6 Admin ULP dibuat. Password: password');
    }
}
