<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================
        // SKEMA: REFERENSI
        // ==========================

        Schema::create('referensi.kategori_unit', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nm_kategori', 100)->unique();
        });

        Schema::create('referensi.unit_kerja', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nm_unit', 125);
            $table->string('kode_unit', 50)->nullable();
            
            // FK ke Kategori Unit
            $table->foreignUuid('kategori_uuid')
                  ->constrained('referensi.kategori_unit', 'UUID')
                  ->cascadeOnDelete();
                  
            $table->boolean('a_aktif')->default(true);
        });

        Schema::create('referensi.jenis_layanan', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nm_layanan', 100);
            $table->text('deskripsi')->nullable();
            $table->boolean('a_aktif')->default(true);
        });

        // Tambahan vital untuk transaksi
        Schema::create('referensi.status_pengajuan', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nm_status', 50); 
        });

        // ==========================
        // SKEMA: AKUN
        // ==========================

        Schema::create('akun.peran', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nm_peran', 50)->unique();
            $table->boolean('a_aktif')->default(true);
        });

        Schema::create('akun.pemetaan_peran_sso', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('atribut_sso', 100); // misal: 'staff@unila'
            
            $table->foreignUuid('peran_uuid')
                  ->constrained('akun.peran', 'UUID')
                  ->cascadeOnDelete();
        });

        Schema::create('akun.pengguna', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nm', 125);
            $table->string('usn', 100)->unique();
            $table->string('email', 125)->unique();
            $table->string('ktp', 20)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('kata_sandi');
            
            $table->foreignUuid('peran_uuid')
                  ->nullable()
                  ->constrained('akun.peran', 'UUID')
                  ->nullOnDelete();
            
            $table->boolean('a_aktif')->default(true);
            
            // Timestamp Custom (Bahasa Indo)
            $table->timestamp('wkt_dibuat')->useCurrent();
            $table->timestamp('wkt_diubah')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        // Drop urutan terbalik dari dependensi
        Schema::dropIfExists('akun.pengguna');
        Schema::dropIfExists('akun.pemetaan_peran_sso');
        Schema::dropIfExists('akun.peran');
        Schema::dropIfExists('referensi.status_pengajuan');
        Schema::dropIfExists('referensi.jenis_layanan');
        Schema::dropIfExists('referensi.unit_kerja');
        Schema::dropIfExists('referensi.kategori_unit');
    }
};