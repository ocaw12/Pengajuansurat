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
        Schema::create('alur_approval', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->cascadeOnDelete();
            $table->integer('urutan');
            $table->foreignId('master_jabatan_id')->constrained('master_jabatan')->cascadeOnDelete();
            $table->string('scope', 20)->default('PRODI')->comment('PRODI, FAKULTAS, UNIVERSITAS');
            $table->timestamps();

            $table->unique(['jenis_surat_id', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alur_approval');
    }
};