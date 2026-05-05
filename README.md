# SIMKPI v2.0 — Sistem Monitoring dan Evaluasi Kinerja KPI
## PT PLN (Persero) UP3 Surakarta

---

## ⚡ Cara Instalasi (XAMPP + Laravel 11)

### Langkah 1 — Persyaratan
Pastikan sudah terpasang:
- **XAMPP** dengan PHP 8.2+ dan MySQL
- **Composer** (https://getcomposer.org)

### Langkah 2 — Ekstrak Proyek
Ekstrak folder `simkpi` ke:
```
C:\xampp\htdocs\simkpi\
```

### Langkah 3 — Install Dependensi
Buka **Command Prompt** di folder proyek:
```bash
cd C:\xampp\htdocs\simkpi
composer install
```

### Langkah 4 — Buat File .env
```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env` sesuai konfigurasi XAMPP Anda:
```
DB_DATABASE=simkpi_pln
DB_USERNAME=root
DB_PASSWORD=         ← kosong jika default XAMPP
```

### Langkah 5 — Buat Database
Buka **phpMyAdmin** (http://localhost/phpmyadmin), lalu jalankan:
```sql
CREATE DATABASE simkpi_pln CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Langkah 6 — Migrasi & Seeder
```bash
php artisan migrate --seed
```

### Langkah 7 — Jalankan Server
```bash
php artisan serve
```
Buka browser: **http://localhost:8000**

---

## 🔑 Akun Default

| Role         | Email                    | Password     |
|--------------|--------------------------|--------------|
| Admin        | admin@pln.local          | password123  |
| Manajer      | manajer@pln.local        | password123  |
| Supervisor 1 | sup.teknik@pln.local     | password123  |
| Supervisor 2 | sup.niaga@pln.local      | password123  |
| Pegawai 1    | pegawai1@pln.local       | password123  |
| Pegawai 2    | pegawai2@pln.local       | password123  |

---

## 📁 Struktur Proyek

```
simkpi/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── KategoriController.php
│   │   │   ├── IndikatorController.php
│   │   │   ├── RealisasiController.php
│   │   │   ├── MonitoringController.php
│   │   │   ├── EvaluasiController.php
│   │   │   ├── LaporanController.php
│   │   │   └── PenggunaController.php
│   │   └── Middleware/
│   │       ├── Authenticate.php
│   │       ├── RedirectIfAuthenticated.php
│   │       └── CheckRole.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Kategori.php
│   │   ├── Indikator.php
│   │   ├── Realisasi.php
│   │   └── Evaluasi.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/
│   └── app.php
├── config/
│   ├── app.php
│   ├── database.php
│   └── session.php
├── database/
│   ├── migrations/
│   │   └── 2024_01_01_000001_create_all_tables.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   ├── index.php
│   └── .htaccess
├── resources/
│   └── views/
│       ├── layouts/app.blade.php
│       ├── auth/login.blade.php
│       ├── dashboard/index.blade.php
│       ├── kpi/
│       │   ├── kategori.blade.php
│       │   ├── indikator.blade.php
│       │   ├── input.blade.php
│       │   └── validasi.blade.php
│       ├── monitoring/index.blade.php
│       ├── evaluasi/
│       │   ├── index.blade.php
│       │   └── detail.blade.php
│       ├── laporan/
│       │   ├── index.blade.php
│       │   └── pdf.blade.php
│       └── pengguna/
│           ├── index.blade.php
│           └── profil.blade.php
├── routes/
│   ├── web.php
│   └── console.php
├── .env.example
└── composer.json
```

---

## 🎯 Fitur per Role

| Fitur                  | Admin | Manajer | Supervisor | Pegawai |
|------------------------|:-----:|:-------:|:----------:|:-------:|
| Dashboard              | ✅    | ✅      | ✅         | ✅      |
| Kelola Kategori KPI    | ✅    | —       | —          | —       |
| Kelola Indikator KPI   | ✅    | —       | —          | —       |
| Validasi Data          | ✅    | —       | —          | —       |
| Input Data Kinerja     | ✅    | ✅      | ✅         | ✅      |
| Monitoring Real-time   | ✅    | ✅      | ✅         | ✅      |
| Evaluasi Kinerja       | ✅    | ✅      | —          | —       |
| Lihat Laporan          | ✅    | ✅      | ✅         | —       |
| Download PDF/Excel     | ✅    | ✅      | ✅         | —       |
| Manajemen Pengguna     | ✅    | —       | —          | —       |

---

## 📊 Indikator KPI

| Kode   | Nama                        | Target  | Bobot | Arah  |
|--------|-----------------------------|---------|-------|-------|
| KS-001 | SAIDI                       | 240 mnt | 15%   | Turun |
| KS-002 | SAIFI                       | 3.5 kali| 15%   | Turun |
| KS-003 | Losses Jaringan             | 4.5%    | 10%   | Turun |
| KN-001 | Rasio Elektrifikasi         | 99.5%   | 10%   | Naik  |
| KN-002 | Realisasi Pendapatan        | 100%    | 10%   | Naik  |
| KN-003 | Piutang Macet               | 0.5%    | 5%    | Turun |
| PP-001 | CSI                         | 4.2     | 10%   | Naik  |
| PP-002 | Response Time Pengaduan     | 3 jam   | 5%    | Turun |
| EO-001 | Realisasi Anggaran O&M      | 95%     | 10%   | Naik  |
| EO-002 | Produktivitas Pegawai       | 2.5 MVA | 5%    | Naik  |
| K3-001 | Zero Accident               | 0       | 10%   | Turun |
| K3-002 | Compliance K3               | 100%    | 5%    | Naik  |

**Total Bobot: 110%** *(dapat disesuaikan via kelola indikator)*

---

## 🏆 Predikat Kinerja

| Skor        | Predikat        |
|-------------|-----------------|
| ≥ 90        | 🟢 Sangat Baik  |
| 80 – 89.99  | 🔵 Baik         |
| 70 – 79.99  | 🟡 Cukup        |
| 60 – 69.99  | 🔴 Kurang       |
| < 60        | ⚫ Sangat Kurang |

---

## 🔧 Troubleshooting

| Error                             | Solusi                                                          |
|-----------------------------------|-----------------------------------------------------------------|
| `composer: not found`             | Install Composer dari https://getcomposer.org                   |
| `SQLSTATE[HY000] [1049]`          | Buat database `simkpi_pln` di phpMyAdmin terlebih dahulu        |
| `No application encryption key`   | Jalankan `php artisan key:generate`                             |
| Halaman 500 setelah migrate       | Pastikan semua extension PHP aktif (openssl, pdo_mysql, mbstring, fileinfo) |
| `php_fileinfo disabled`           | Aktifkan di `php.ini` XAMPP: hapus `;` sebelum `extension=fileinfo` |
| Halaman 404 (XAMPP Apache)        | Aktifkan `mod_rewrite` di `httpd.conf` XAMPP                   |
| Tidak bisa download PDF           | Pastikan `barryvdh/laravel-dompdf` terinstall via `composer install` |

### Aktifkan Extension PHP di XAMPP
Buka `C:\xampp\php\php.ini`, cari dan hapus tanda `;` dari:
```
extension=fileinfo
extension=pdo_mysql
extension=mbstring
extension=openssl
extension=zip
```

Restart Apache setelah mengubah `php.ini`.

---

*Dibuat oleh: Mahasiswa Magang Informatika — PLN UP3 Surakarta, 2025*
