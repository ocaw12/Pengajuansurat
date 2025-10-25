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
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat', 20)->unique();
            $table->string('nama_surat', 255);
            $table->enum('kategori', ['Akademik', 'Kemahasiswaan', 'Keuangan', 'Penelitian', 'Umum'])->default('Umum');
            $table->text('isi_template');
            $table->json('form_schema')->nullable();

            // Kolom untuk penomoran surat
            $table->string('format_penomoran', 255)->default('{nomor_urut}/{kode_surat}/{kode_unit}/{bulan_romawi}/{tahun}');
            $table->integer('counter_nomor_urut')->default(0);
            $table->year('counter_tahun')->nullable()->comment('Tahun counter terakhir digunakan'); // Menggunakan tipe year

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_surat');
    }
};