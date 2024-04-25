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
        Schema::create('cursos_profesores', function (Blueprint $table) {
            $table->BigIncrements('id_curso_profesor');
            $table->unsignedBigInteger('id_curso');
            $table->unsignedBigInteger('id_personal_instituto');
            $table->date('fecha_asignacion')->nullable(false);
            $table->date('fecha_finalizacion')->nullable(true);
            $table->boolean('estado')->default(true);
            $table->foreign('id_curso')->references('id_curso')->on('cursos');
            $table->foreign('id_personal_instituto')->references('id_personal_instituto')->on('personal_instituto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos_profesores');
    }
};
