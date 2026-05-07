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
       Schema::create('semester', function (Blueprint $table) {
    $table->id();

    $table->foreignId('tahun_ajaran_id')
          ->constrained('tahun_ajaran')
          ->onDelete('cascade');

    $table->enum('semester', ['GANJIL', 'GENAP']);

    $table->boolean('is_aktif')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester');
    }
};
