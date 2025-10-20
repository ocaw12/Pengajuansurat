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
    Schema::create('prodi', function (Blueprint $table) {
        $table->string('id_prodi')->primary();
        $table->string('nama_prodi');
        $table->string('email_prodi')->unique();
        $table->string('id_fakultas'); // foreign key

        $table->foreign('id_fakultas')
              ->references('id_fakultas')
              ->on('fakultas')
              ->onDelete('cascade');

        $table->timestamps();
    });
}

};
