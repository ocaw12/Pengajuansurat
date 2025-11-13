<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->cascadeOnDelete();
            $table->foreignId('admin_validator_id')->nullable()->constrained('admin_staff')->nullOnDelete();
            $table->string('nomor_surat', 100)->unique()->nullable();
            $table->dateTime('tanggal_pengajuan')->useCurrent();
            $table->text('keperluan');
            $table->json('data_pendukung')->nullable();

            $table->enum('status_pengajuan', [
                'pending',
                'divalidasi_admin',
                'menunggu_pejabat',
                'perlu_revisi',
                'ditolak',
                'selesai', 
                'siap_dicetak', // <-- TAMBAHKAN INI
                'siap_diambil', 
                'sudah_diambil',
                'gagal_generate'
            ])->default('pending');

            $table->string('file_hasil_pdf', 255)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_revisi')->nullable();

            // Kolom untuk metode pengambilan
            $table->enum('metode_pengambilan', ['digital', 'cetak'])->default('digital');
            $table->dateTime('tanggal_diambil')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};