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
        Schema::create('cursos_estudiantes', function (Blueprint $table) {
            $table->BigIncrements('id_curso_estudiante');
            $table->unsignedBigInteger('id_curso');
            $table->unsignedBigInteger('id_estudiante');
            $table->date('fecha_inscripcion')->nullable(false);
            $table->date('fecha_finalizacion')->nullable(true);
            $table->boolean('estado')->default(true);
            $table->foreign('id_curso')->references('id_curso')->on('cursos');
            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiantes');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos_estudiantes');
    }
};
