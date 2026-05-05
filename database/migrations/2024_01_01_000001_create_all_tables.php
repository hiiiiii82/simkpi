<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // USERS
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role',['admin','manajer','supervisor','pegawai'])->default('pegawai');
            $table->string('nip',20)->nullable()->unique();
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // ULP (Unit Layanan Pelanggan)
        Schema::create('ulps', function (Blueprint $table) {
            $table->id();
            $table->string('nama');        // SURAKARTA KOTA, MANAHAN, dll
            $table->string('kode',10);     // SKT, MNH, KTS, PLR, SRG, SBL
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // KATEGORIS KPI
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode',10)->unique();
            $table->string('warna',7)->default('#1D4ED8');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // INDIKATORS KPI UP3
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->string('nama');
            $table->string('kode',20)->unique();
            $table->string('satuan',60);
            $table->decimal('target',14,4)->default(0);       // bisa negatif
            $table->decimal('bobot',5,2)->default(0);
            $table->enum('arah',['naik','turun'])->default('naik');
            $table->enum('periode',['bulanan','triwulan','tahunan'])->default('bulanan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // INDIKATORS KPI ULP (per ULP bisa berbeda target)
        Schema::create('indikator_ulps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained('indikators')->cascadeOnDelete();
            $table->foreignId('ulp_id')->constrained('ulps')->cascadeOnDelete();
            $table->decimal('target',14,4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['indikator_id','ulp_id']);
        });

        // REALISASIS UP3
        Schema::create('realisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained('indikators')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->decimal('nilai',14,4);
            $table->decimal('target_snapshot',14,4)->default(0);
            $table->decimal('capaian',8,2)->nullable();
            $table->decimal('skor',8,2)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status',['draft','submitted','approved','rejected'])->default('submitted');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            $table->unique(['indikator_id','bulan','tahun']);
        });

        // REALISASIS ULP (per indikator per ULP)
        Schema::create('realisasi_ulps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained('indikators')->cascadeOnDelete();
            $table->foreignId('ulp_id')->constrained('ulps')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->decimal('nilai',14,4)->nullable();
            $table->decimal('target_snapshot',14,4)->default(0);
            $table->decimal('capaian',8,2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['indikator_id','ulp_id','bulan','tahun']);
        });

        // EVALUASIS
        Schema::create('evaluasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->decimal('total_skor',8,2)->default(0);
            $table->decimal('total_proporsional',8,2)->default(0);
            $table->string('predikat',20)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dievaluasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status',['proses','selesai'])->default('selesai');
            $table->timestamps();
            $table->unique(['bulan','tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasis');
        Schema::dropIfExists('realisasi_ulps');
        Schema::dropIfExists('realisasis');
        Schema::dropIfExists('indikator_ulps');
        Schema::dropIfExists('indikators');
        Schema::dropIfExists('kategoris');
        Schema::dropIfExists('ulps');
        Schema::dropIfExists('users');
    }
};