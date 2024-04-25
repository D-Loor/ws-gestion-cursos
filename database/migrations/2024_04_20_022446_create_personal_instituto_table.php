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
        Schema::create('personal_instituto', function (Blueprint $table) {
            $table->BigIncrements('id_personal_instituto');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_rol');
            $table->string('password')->nullable(false);
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('id_rol')->references('id_rol')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_instituto');
    }
};
