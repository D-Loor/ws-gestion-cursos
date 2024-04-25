<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Roles
        DB::table('roles')->insert([
            ['rol' => 'Administrador'],
            ['rol' => 'Profesor'],
        ]);

        //Usuarios
        DB::table('usuarios')->insert([
            ['nombre' => 'Juan', 'apellido' => 'Perez', 'email' => 'juan@gmail.com'],
            ['nombre' => 'Maria', 'apellido' => 'Gomez', 'email' => 'maria@gmail.com'],
            ['nombre' => 'Pedro', 'apellido' => 'Gonzalez', 'email' => 'pedro@gmail.com'],
            ['nombre' => 'Ana', 'apellido' => 'Rodriguez', 'email' => 'ana@gmail.com'],
            ['nombre' => 'Carlos', 'apellido' => 'Hernandez', 'email' => 'carlos@gmail.com'],
        ]);

        //Personal del Instituto
        DB::table('personal_instituto')->insert([
            ['id_usuario' => 1, 'id_rol' => 1, 'password' => Hash::make('Juan123.')],
            ['id_usuario' => 2, 'id_rol' => 2, 'password' => Hash::make('Maria123.')],
        ]);

        //Estudiantes
        DB::table('estudiantes')->insert([
            ['id_usuario' => 3, 'edad' => 20, 'ci' => '12473529429'],
            ['id_usuario' => 4, 'edad' => 21, 'ci' => '23467845678'],
            ['id_usuario' => 5, 'edad' => 22, 'ci' => '34567856789'],
        ]);

        //Modalidades
        DB::table('modalidades')->insert([
            ['modalidad' => 'Presencial', 'estado' => true],
            ['modalidad' => 'Virtual', 'estado' => true],
            ['modalidad' => 'Hibrido', 'estado' => false],
        ]);

        //Cursos
        DB::table('cursos')->insert([
            ['nombre' => 'Laravel', 'id_modalidad' => 1, 'hora_inicio' => '08:00:00', 'hora_fin' => '12:00:00', 'fecha_inicio' => '2024-04-25', 'fecha_fin' => '2024-05-10', 'inscritos' => 1, 'estado' => true],
            ['nombre' => 'Angular.JS', 'id_modalidad' => 2, 'hora_inicio' => '14:00:00', 'hora_fin' => '18:00:00', 'fecha_inicio' => '2024-04-26', 'fecha_fin' => '2024-05-17', 'inscritos' => 2, 'estado' => true],
            ['nombre' => 'React.JS', 'id_modalidad' => 3, 'hora_inicio' => '08:00:00', 'hora_fin' => '12:00:00', 'fecha_inicio' => '2024-06-03', 'fecha_fin' => '2024-06-28', 'inscritos' => 0, 'estado' => false],
            ['nombre' => 'Vue.JS', 'id_modalidad' => 2, 'hora_inicio' => '14:00:00', 'hora_fin' => '18:00:00', 'fecha_inicio' => '2024-04-03', 'fecha_fin' => '2024-06-28', 'inscritos' => 0, 'estado' => false],
        ]);

        //Profesores asignados a los cursos
        DB::table('cursos_profesores')->insert([
            ['id_curso' => 1, 'id_personal_instituto' => 2, 'fecha_asignacion' => '2024-04-25', 'fecha_finalizacion' => '2024-05-10', 'estado' => true],
            ['id_curso' => 2, 'id_personal_instituto' => 2, 'fecha_asignacion' => '2024-04-26', 'fecha_finalizacion' => '2024-05-17', 'estado' => true],
        ]);

        //Estudiantes inscritos a los cursos
        DB::table('cursos_estudiantes')->insert([
            ['id_curso' => 1, 'id_estudiante' => 2, 'fecha_inscripcion' => '2024-04-20', 'fecha_finalizacion' => NULL, 'estado' => true],
            ['id_curso' => 2, 'id_estudiante' => 1, 'fecha_inscripcion' => '2024-04-21', 'fecha_finalizacion' => NULL, 'estado' => true],
            ['id_curso' => 2, 'id_estudiante' => 3, 'fecha_inscripcion' => '2024-04-14', 'fecha_finalizacion' => NULL, 'estado' => true],
        ]);
    }
}
