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
        // SKEMA: TRANSAKSI
        // ==========================

        Schema::create('transaksi.pengajuan', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('no_tiket', 50)->unique();
            
            // Relasi ke Akun
            $table->foreignUuid('pengguna_uuid')
                  ->constrained('akun.pengguna', 'UUID');
            
            // Relasi ke Referensi
            $table->foreignUuid('unit_kerja_uuid')
                  ->constrained('referensi.unit_kerja', 'UUID');
                  
            $table->foreignUuid('jenis_layanan_uuid')
                  ->constrained('referensi.jenis_layanan', 'UUID');
            
            $table->foreignUuid('status_uuid')
                  ->constrained('referensi.status_pengajuan', 'UUID');
            
            $table->date('tgl_pengajuan')->useCurrent();
            
            $table->timestamp('wkt_dibuat')->useCurrent();
            $table->timestamp('wkt_diubah')->nullable()->useCurrentOnUpdate();
        });

        Schema::create('transaksi.rincian_pengajuan', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            
            // One-to-One dengan Pengajuan
            $table->foreignUuid('pengajuan_uuid')
                  ->unique()
                  ->constrained('transaksi.pengajuan', 'UUID')
                  ->cascadeOnDelete();
            
            $table->string('nm_domain', 150)->nullable();
            $table->string('alamat_ip', 45)->nullable();
            $table->string('kapasitas_penyimpanan', 50)->nullable();
            $table->string('lokasi_server', 100)->nullable();
            $table->text('keterangan_keperluan')->nullable();
            $table->string('file_lampiran', 255)->nullable();
        });

        // ==========================
        // SKEMA: AUDIT
        // ==========================

        Schema::create('audit.riwayat_pengajuan', function (Blueprint $table) {
            $table->uuid('UUID')->primary()->default(DB::raw('gen_random_uuid()'));
            
            $table->foreignUuid('pengajuan_uuid')
                  ->constrained('transaksi.pengajuan', 'UUID')
                  ->cascadeOnDelete();
            
            // Status Lama & Baru (Bisa Null jika status awal)
            $table->foreignUuid('status_lama_uuid')
                  ->nullable()
                  ->constrained('referensi.status_pengajuan', 'UUID');

            $table->foreignUuid('status_baru_uuid')
                  ->nullable()
                  ->constrained('referensi.status_pengajuan', 'UUID');
            
            $table->foreignUuid('diubah_oleh_uuid')
                  ->nullable()
                  ->constrained('akun.pengguna', 'UUID');
                  
            $table->text('catatan_log')->nullable();
            $table->timestamp('wkt_kejadian')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit.riwayat_pengajuan');
        Schema::dropIfExists('transaksi.rincian_pengajuan');
        Schema::dropIfExists('transaksi.pengajuan');
    }
};