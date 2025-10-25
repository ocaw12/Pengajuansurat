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
        Schema::table('users', function (Blueprint $table) {
            // KODE INI YANG BENAR:
            // 1. $table->foreignId('role_id') -> Membuat kolom 'role_id' di tabel 'users'
            // 2. ->constrained('roles') -> Menghubungkannya ke 'id' di tabel 'roles'
            $table->foreignId('role_id')
                  ->after('email_verified_at') // (Opsional, hanya untuk posisi)
                  ->constrained('roles')      // Ini akan merujuk ke 'id' di tabel 'roles'
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini adalah kebalikan dari kode 'up'
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};