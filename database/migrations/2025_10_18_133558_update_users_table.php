<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id_user', 15)->primary(); // PK sebagai VARCHAR
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->unsignedBigInteger('id_role');  // FK ke roles
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_role')->references('id_role')->on('roles'); // Foreign key
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
