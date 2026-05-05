<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now   = now();
        $tahun = 2026;
        $bulan = 1; // Januari

        /* ── USERS ─────────────────────────────────────────────── */
        DB::table('users')->insert([
            ['name'=>'Administrator',        'email'=>'admin@pln.local',      'role'=>'admin',      'nip'=>'8212001','jabatan'=>'System Administrator',  'unit_kerja'=>'IT',        'password'=>Hash::make('password123'),'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Ir. Budi Santoso MT',  'email'=>'manajer@pln.local',    'role'=>'manajer',    'nip'=>'8212002','jabatan'=>'Manajer UP3 Surakarta',  'unit_kerja'=>'Manajemen', 'password'=>Hash::make('password123'),'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Andi Prasetyo ST',     'email'=>'sup.teknik@pln.local', 'role'=>'supervisor', 'nip'=>'8212003','jabatan'=>'Supervisor Teknik',       'unit_kerja'=>'Teknik',    'password'=>Hash::make('password123'),'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Dewi Rahayu SE',       'email'=>'sup.niaga@pln.local',  'role'=>'supervisor', 'nip'=>'8212004','jabatan'=>'Supervisor Niaga',        'unit_kerja'=>'Niaga',     'password'=>Hash::make('password123'),'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Rizky Firmansyah',     'email'=>'pegawai1@pln.local',   'role'=>'pegawai',    'nip'=>'8212005','jabatan'=>'Pelaksana Teknik',        'unit_kerja'=>'Teknik',    'password'=>Hash::make('password123'),'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Sari Indrawati',       'email'=>'pegawai2@pln.local',   'role'=>'pegawai',    'nip'=>'8212006','jabatan'=>'Pelaksana Niaga',         'unit_kerja'=>'Niaga',     'password'=>Hash::make('password123'),'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
        ]);

        /* ── ULP ─────────────────────────────────────────────────── */
        DB::table('ulps')->insert([
            ['nama'=>'SURAKARTA KOTA', 'kode'=>'SKT', 'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['nama'=>'MANAHAN',        'kode'=>'MNH', 'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['nama'=>'KARTOSURA',      'kode'=>'KTS', 'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['nama'=>'PALUR',          'kode'=>'PLR', 'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['nama'=>'SRAGEN',         'kode'=>'SRG', 'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['nama'=>'SUMBERLAWANG',   'kode'=>'SBL', 'is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
        ]);

        /* ── KATEGORIS ─────────────────────────────────────────── */
        DB::table('kategoris')->insert([
            ['nama'=>'Key Performance Indicator (KPI)', 'kode'=>'KPI','warna'=>'#1D4ED8','deskripsi'=>'KPI Utama UP3 Surakarta 2026','is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
            ['nama'=>'Performance Indicator (PI)',       'kode'=>'PI', 'warna'=>'#065F46','deskripsi'=>'PI Pendukung UP3 Surakarta 2026','is_active'=>true,'created_at'=>$now,'updated_at'=>$now],
        ]);

        /* ── INDIKATORS UP3 (sesuai sheet Kinerja UP3 Excel) ───── */
        // [kat_id, nama, kode, satuan, target_jan, bobot_jan, arah, periode]
        $inds = [
            // KPI
            [1,'Penjualan Tenaga Listrik',                              'KPI-01','GWh',        -17.56,  14, 'naik',  'bulanan'],
            [1,'Susut Distribusi Tanpa E-min (sesuai kewenangan)',      'KPI-02','%',             4.45,  12, 'turun', 'bulanan'],
            [1,'SAIDI (sesuai kewenangan)',                             'KPI-03','menit/plg',    9.14,    5, 'turun', 'bulanan'],
            [1,'SAIFI (sesuai kewenangan)',                             'KPI-04','kali/plg',     0.10,    5, 'turun', 'bulanan'],
            [1,'ENS (sesuai kewenangan)',                               'KPI-05','MWh',          40.91,   2, 'turun', 'bulanan'],
            [1,'Penambahan Aset RUPTL',                                 'KPI-06','%',             1.6932,  0, 'naik',  'bulanan'],
            [1,'Penambahan Aset Penyelesaian Fisik Investasi',          'KPI-07','%',           100.0,    0, 'naik',  'bulanan'],
            [1,'Percepatan Penyambungan Pelanggan',                    'KPI-08','%',           100.0,   10, 'naik',  'bulanan'],
            [1,'Jumlah Penambahan Pelanggan dan Penambahan Daya',      'KPI-09','%',             0.0,    0, 'naik',  'bulanan'],
            [1,'Pendapatan Biaya Penyambungan',                        'KPI-10','Rp Miliar',     8.38,   0, 'naik',  'bulanan'],
            [1,'Penambahan Jumlah Pelanggan Lisdes',                   'KPI-11','Pelanggan',     0.0,    0, 'naik',  'bulanan'],
            [1,'Peningkatan kWh Penjualan dari Pelanggan Lisdes',      'KPI-12','Rp Miliar',     0.0,    0, 'naik',  'bulanan'],
            [1,'Peningkatan Pelayanan Pelanggan',                      'KPI-13','%',           100.0,    8, 'naik',  'bulanan'],
            [1,'Feedback Rating Negatif PLN Mobile - Gangguan',        'KPI-14','%',             5.0,    0, 'turun', 'bulanan'],
            [1,'Response Time atas Gangguan (diluar Clear Tamper)',     'KPI-15','%',            30.0,    0, 'turun', 'bulanan'],
            [1,'Success Rate Auto Dispatch Gangguan Individual',       'KPI-16','kali/plg',     16.92,   0, 'naik',  'bulanan'],
            [1,'Jumlah Kali Transaksi Keuangan melalui PLN Mobile',    'KPI-17','Kali',        23255,    0, 'naik',  'bulanan'],
            // PI
            [2,'Gangguan TM (sesuai kewenangan)',                      'PI-01', 'kali',         121,     5, 'turun', 'bulanan'],
            [2,'Kerusakan Peralatan Distribusi (sesuai kewenangan)',   'PI-02', '%',              0.0,    2, 'turun', 'bulanan'],
            [2,'MVOD / ERT Distribusi (sesuai kewenangan)',            'PI-03', '%',            100.0,   4, 'naik',  'bulanan'],
            [2,'MTTR Siaga 1 TM (sesuai kewenangan)',                  'PI-04', '%',            100.0,   2, 'naik',  'bulanan'],
            [2,'Pencapaian Saldo Rata-Rata Akhir Bulan',               'PI-05', '%',             50.0,   0, 'naik',  'bulanan'],
            [2,'Pencapaian Pelunasan PRR, Ex-PRR, Piutang Prabayar',  'PI-06', '%',              0.0,    0, 'naik',  'bulanan'],
            [2,'Usulan Penghapusan PRR',                               'PI-07', 'Rp Juta',        0.0,    0, 'naik',  'bulanan'],
            [2,'Pengendalian Anggaran Investasi sesuai RKAP',          'PI-08', 'Rp Miliar',     3.9136,  2, 'naik',  'bulanan'],
            [2,'Usulan Penghapusan ATTB',                              'PI-09', 'Pelanggan',   1895.0,   2, 'naik',  'bulanan'],
            [2,'Pengendalian NAC (Non Allowable Cost)',                'PI-10', 'Pelanggan',    855.0,   1, 'naik',  'bulanan'],
            [2,'Perolehan kWh P2TL',                                   'PI-11', 'Rp Juta',        0.0,   3, 'naik',  'bulanan'],
            [2,'Penyelesaian Ganti Meter',                             'PI-12', 'Unit',           0.0,   2, 'naik',  'bulanan'],
            [2,'Tindak Lanjut LBKB (Laporan Bulanan Kelainan Baca)',   'PI-13', '%',              0.0,   1, 'naik',  'bulanan'],
            [2,'Pengembangan Aset Distribusi (EAM)',                   'PI-14', '%',              0.0,   0, 'naik',  'bulanan'],
            [2,'Efektivitas Pemeliharaan (Value Creation AMI)',         'PI-15', '%',              0.0,   0, 'naik',  'bulanan'],
        ];

        foreach ($inds as [$kid,$nama,$kode,$satuan,$target,$bobot,$arah,$periode]) {
            DB::table('indikators')->insert([
                'kategori_id'=>$kid,'nama'=>$nama,'kode'=>$kode,
                'satuan'=>$satuan,'target'=>$target,'bobot'=>$bobot,
                'arah'=>$arah,'periode'=>$periode,
                'is_active'=>true,'created_at'=>$now,'updated_at'=>$now,
            ]);
        }

        /* ── INDIKATOR ULP - target per ULP (dari sheet Target) ─── */
        // ULP ids: SKT=1, MNH=2, KTS=3, PLR=4, SRG=5, SBL=6
        // KPI-01: Penjualan per ULP
        // KPI-03: SAIDI per ULP (target Jan 2026 = target bulan Januari dari sheet Target)
        $targetUlp = [
            // [ind_id, ulp_id, target]
            // Penjualan (KPI-01, id=1)
            [1,1, 43.168601],[1,2, 57.341612],[1,3, 33.966361],[1,4, 75.263055],[1,5, 62.691912],[1,6, 33.553898],
            // Susut (KPI-02, id=2) - target Jan per ULP
            [2,1, 2.69],[2,2, 2.02],[2,3, 6.69],[2,4, 1.07],[2,5, 4.79],[2,6, 8.98],
            // SAIDI (KPI-03, id=3) - target Jan per ULP
            [3,1, 3.6],[3,2, 4.42],[3,3, 5.86],[3,4, 3.07],[3,5, 10.12],[3,6, 18.23],
            // SAIFI (KPI-04, id=4)
            [4,1, 0.05],[4,2, 0.07],[4,3, 0.10],[4,4, 0.05],[4,5, 0.08],[4,6, 0.15],
            // ENS (KPI-05, id=5)
            [5,1, 5.58],[5,2, 18.11],[5,3, 10.32],[5,4, 7.57],[5,5, 10.61],[5,6, 7.15],
        ];
        foreach ($targetUlp as [$indId,$ulpId,$target]) {
            DB::table('indikator_ulps')->insert([
                'indikator_id'=>$indId,'ulp_id'=>$ulpId,'target'=>$target,
                'is_active'=>true,'created_at'=>$now,'updated_at'=>$now,
            ]);
        }

        /* ── REALISASIS UP3 Januari 2026 (dari sheet Realisasi Excel) ─ */
        $realisasiUp3 = [
            // [ind_id, nilai, keterangan]
            [1,  305.9854, 'Total penjualan UP3 Surakarta Januari 2026'],
            [2,  4.47,     'Susut distribusi tanpa E-min UP3 Januari 2026'],
            [3,  0.10496,  'SAIDI UP3 gabungan 6 ULP (menit/plg)'],
            [4,  0.002575, 'SAIFI UP3 gabungan 6 ULP (kali/plg)'],
            [5,  5.602,    'ENS UP3 gabungan 6 ULP (MWh)'],
            [6,  1.8227,   'Realisasi penambahan aset RUPTL (%)'],
            [7,  76.09,    'Penyelesaian fisik investasi (%)'],
            [8,  86.98,    'Capaian percepatan penyambungan pelanggan (%)'],
            [9,  85.18,    'Penambahan pelanggan & daya tersambung (%)'],
            [10, 141.0,    'Pendapatan biaya penyambungan (Rp Miliar)'],
            [11, 9.0,      'Penambahan pelanggan lisdes'],
            [12, 9.0,      'Peningkatan kWh penjualan lisdes (Rp Miliar)'],
            [13, 0.0,      'Peningkatan pelayanan pelanggan'],
            [14, 0.0,      'Feedback rating negatif PLN Mobile gangguan (%)'],
            [15, 0.0,      'Response time gangguan (%)'],
            [16, 0.6106,   'Success rate auto dispatch (kali/plg)'],
            [17, 40261.0,  'Total transaksi keuangan PLN Mobile (kali)'],
            [18, 3593.0,   'Gangguan TM sesuai kewenangan (kali)'],
            [19, 17.32,    'Kerusakan peralatan distribusi (%)'],
            [20, 0.0,      'MVOD / ERT Distribusi (%)'],
            [21, 100.0,    'MTTR Siaga 1 TM (%)'],
            [22, 100.0,    'Saldo rata-rata akhir bulan (%)'],
            [23, 0.0,      'Pencapaian pelunasan PRR (%)'],
            [24, 0.0,      'Usulan penghapusan PRR (Rp Juta)'],
            [25, 2.9816,   'Realisasi anggaran investasi (Rp Miliar)'],
            [26, 536.0,    'Usulan penghapusan ATTB (Pelanggan)'],
            [27, 0.0,      'Pengendalian NAC (Pelanggan)'],
            [28, 61.80,    'Perolehan kWh P2TL (Rp Juta)'],
            [29, 1264.0,   'Penyelesaian ganti meter (unit)'],
            [30, 100.0,    'Tindak lanjut LBKB (%)'],
            [31, 100.0,    'Pengembangan EAM distribusi (%)'],
            [32, 0.0,      'Value creation AMI (%)'],
        ];

        foreach ($realisasiUp3 as [$indId, $nilai, $ket]) {
            $ind = DB::table('indikators')->where('id',$indId)->first();
            if (!$ind) continue;
            $target = (float)$ind->target;
            $capaian = $this->hitungCapaian($nilai, $target, $ind->arah);
            $skor    = round(($capaian / 100) * (float)$ind->bobot, 2);

            DB::table('realisasis')->insert([
                'indikator_id'=>$indId,'user_id'=>3,
                'bulan'=>$bulan,'tahun'=>$tahun,
                'nilai'=>$nilai,'target_snapshot'=>$target,
                'capaian'=>$capaian,'skor'=>$skor,'keterangan'=>$ket,
                'status'=>'approved','validated_by'=>2,'validated_at'=>$now,
                'created_at'=>$now,'updated_at'=>$now,
            ]);
        }

        /* ── REALISASI ULP per indikator (dari sheet Realisasi Excel) ── */
        // ULP: SKT=1, MNH=2, KTS=3, PLR=4, SRG=5, SBL=6
        // [ind_id, [SKT, MNH, KTS, PLR, SRG, SBL]]
        $realisasiUlp = [
            // Penjualan (KPI-01)
            [1,  [43.168601, 57.341612, 33.966361, 75.263055, 62.691912, 33.553898]],
            // SAIDI (KPI-03)
            [3,  [0.012990,  0.025439,  0.053925,  0.071383,  0.233956,  0.119547]],
            // SAIFI (KPI-04)
            [4,  [0.000149,  0.000948,  0.001124,  0.003483,  0.005195,  0.002951]],
            // ENS (KPI-05)
            [5,  [0.561,     0.954,     0.374,     1.408,     1.629,     0.676]],
            // Susut Distribusi (KPI-02) - per ULP
            [2,  [1.8227,    1.4169,    6.0370,    2.8261,    4.0057,    9.9559]],
            // Perolehan Temuan P2TL (PI-11 terkait)
            [11, [null,      null,      null,      null,      null,      null]],
            // Penyelesaian Temuan P2TL
            [8,  [100.0,     85.177,    100.0,     100.0,     75.132,    100.0]],
            // Penyelesaian Ganti Meter (PI-12, ind=29)
            [29, [381.0,     141.0,     179.0,     184.0,     303.0,     76.0]],
            // Gangguan TM > 5 menit (PI-01, ind=18)
            [18, [9.0,       18.0,      11.0,      6.0,       19.0,      9.0]],
            // Gangguan TM ≤ 5 menit
            [19, [9.0,       22.0,      7.0,       8.0,       40.0,      22.0]],
            // Kerusakan Peralatan (PI-02, ind=20... mapped to id=19 in indikators)
            // Feedback Rating Negatif (KPI-14, ind=14)
            [14, [0.0,       0.0,       0.0,       0.0,       0.0,       0.0]],
            // Gangguan Berulang PLN Mobile
            [15, [0.0,       0.0,       0.6106,    0.0,       0.1033,    0.0]],
            // Rating PLN Mobile
            [16, [4.9,       4.9,       4.9,       4.9,       4.9,       4.9]],
            // Transaksi Keuangan PLN Mobile
            [17, [8436.0,    9354.0,    8367.0,    3593.0,    5848.0,    4663.0]],
            // Response Time Gangguan (menit)
            [15, [12.0,      16.66,     17.32,     11.95,     16.69,     16.40]],
            // MV Outage Duration
            [20, [100.0,     100.0,     100.0,     100.0,     100.0,     100.0]],
            // Penormalan Siaga 1
            [21, [100.0,     100.0,     100.0,     100.0,     100.0,     100.0]],
            // Penambahan Daya Tersambung (MVA)
            [9,  [0.6951,    1.5748,    2.9817,    -1.0738,   2.3696,    1.3490]],
            // Penambahan Jumlah Pelanggan
            [10, [238.0,     728.0,     536.0,     254.0,     690.0,     573.0]],
            // Swa Cam
            [27, [14408.0,   12213.0,   9937.0,    6207.0,    23019.0,   18713.0]],
            // LBKB
            [30, [100.0,     100.0,     100.0,     100.0,     100.0,     100.0]],
        ];

        $ulpIds = [1,2,3,4,5,6]; // SKT, MNH, KTS, PLR, SRG, SBL
        foreach ($realisasiUlp as [$indId, $nilais]) {
            $ind = DB::table('indikators')->where('id',$indId)->first();
            if (!$ind) continue;
            foreach ($ulpIds as $idx => $ulpId) {
                $nilai = $nilais[$idx] ?? null;
                if ($nilai === null) continue;
                // cari target ULP spesifik atau pakai target indikator
                $indUlp = DB::table('indikator_ulps')
                    ->where('indikator_id',$indId)->where('ulp_id',$ulpId)->first();
                $target = $indUlp ? (float)$indUlp->target : (float)$ind->target;
                $capaian = $this->hitungCapaian($nilai, $target, $ind->arah);

                // cek unik
                $exists = DB::table('realisasi_ulps')
                    ->where('indikator_id',$indId)->where('ulp_id',$ulpId)
                    ->where('bulan',$bulan)->where('tahun',$tahun)->exists();
                if ($exists) continue;

                DB::table('realisasi_ulps')->insert([
                    'indikator_id'=>$indId,'ulp_id'=>$ulpId,'user_id'=>3,
                    'bulan'=>$bulan,'tahun'=>$tahun,
                    'nilai'=>$nilai,'target_snapshot'=>$target,'capaian'=>$capaian,
                    'created_at'=>$now,'updated_at'=>$now,
                ]);
            }
        }

        /* ── EVALUASI Januari 2026 ─────────────────────────────── */
        $totalSkor = round(DB::table('realisasis')
            ->where('bulan',$bulan)->where('tahun',$tahun)->where('status','approved')
            ->sum('skor'), 2);

        // Total bobot = 78, proporsional ke 100
        $totalBobot = DB::table('indikators')->sum('bobot');
        $proporsional = $totalBobot > 0 ? round($totalSkor / $totalBobot * 100, 2) : 0;

        $predikat = match(true) {
            $proporsional >= 90 => 'Sangat Baik',
            $proporsional >= 80 => 'Baik',
            $proporsional >= 70 => 'Cukup',
            $proporsional >= 60 => 'Kurang',
            default             => 'Sangat Kurang',
        };

        DB::table('evaluasis')->insert([
            'bulan'=>$bulan,'tahun'=>$tahun,
            'total_skor'=>$totalSkor,'total_proporsional'=>$proporsional,
            'predikat'=>$predikat,
            'catatan'=>"Evaluasi kinerja periode Januari {$tahun}. Total skor: {$totalSkor}, Proporsional: {$proporsional}.",
            'dievaluasi_oleh'=>2,'status'=>'selesai',
            'created_at'=>$now,'updated_at'=>$now,
        ]);
    }

    private function hitungCapaian(float $nilai, float $target, string $arah): float
    {
        if ($arah === 'turun') {
            if ($target <= 0) return $nilai <= 0 ? 110.0 : 0.0;
            return min(round(($target / max(abs($nilai), 0.0001)) * 100, 2), 150.0);
        } else {
            if ($target == 0) return $nilai > 0 ? 110.0 : 100.0;
            return min(round(($nilai / $target) * 100, 2), 150.0);
        }
    }
}