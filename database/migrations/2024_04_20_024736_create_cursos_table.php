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
        Schema::create('cursos', function (Blueprint $table) {
            $table->BigIncrements('id_curso');
            $table->unsignedBigInteger('id_modalidad');
            $table->string('nombre', 50)->nullable(false);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->date('fecha_inicio')->nullable(false);
            $table->date('fecha_fin')->nullable(false);
            $table->integer('inscritos')->default(0);
            $table->boolean('estado')->default(true);
            $table->foreign('id_modalidad')->references('id_modalidad')->on('modalidades');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
