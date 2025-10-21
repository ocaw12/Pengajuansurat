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
       Schema::create('staff_jurusan', function (Blueprint $table) {
        $table->string('id_staff')->primary();
        $table->string('nama_staff');
        $table->string('id_prodi');
        $table->string('id_user');
        $table->timestamps();
         $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
});

    }

};
