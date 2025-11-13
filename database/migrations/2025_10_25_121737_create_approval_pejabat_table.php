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
        Schema::create('approval_pejabat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surat')->cascadeOnDelete();
            $table->foreignId('pejabat_id')->constrained('pejabat')->cascadeOnDelete();
            $table->integer('urutan_approval');
            $table->string('status_approval', 20)->default('menunggu')->comment('menunggu, disetujui, ditolak');
            $table->dateTime('tanggal_approval')->nullable();
            $table->text('catatan_pejabat')->nullable();
            $table->string('path_qr')->nullable()->comment('Path ke file gambar QR Code verifikasi');
            $table->string('kode_verifikasi')->nullable()->comment('Kode unik untuk verifikasi URL');
            $table->timestamps();

            $table->unique(['pengajuan_surat_id', 'pejabat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_pejabat');
    }
};