<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->string('id_user', 15);  // FK ke tabel users
            $table->string('id_Admin', 20)->unique();  // NPM atau NIK yang digunakan untuk login
            $table->string('nama', 100);  // Nama admin
            $table->timestamps();

            // Hubungkan dengan tabel `users`
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
