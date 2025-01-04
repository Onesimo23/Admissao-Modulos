<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [
            ['id' => 1, 'code' => '1', 'name' => 'Licenciatura em Psicologia'],
            ['id' => 2, 'code' => '2', 'name' => 'Licenciatura em Ensino Básico'],
            ['id' => 3, 'code' => '3', 'name' => 'Licenciatura em Administração e Gestão da Educação'],
            ['id' => 4, 'code' => '4', 'name' => 'Licenciatura em Ciências de Educação'],
            ['id' => 5, 'code' => '5', 'name' => 'Licenciatura em História'],
            ['id' => 6, 'code' => '6', 'name' => 'Licenciatura em Inglês'],
            ['id' => 7, 'code' => '7', 'name' => 'Licenciatura em Português'],
            ['id' => 8, 'code' => '8', 'name' => 'Licenciatura em Medicina Veterinária'],
            ['id' => 9, 'code' => '9', 'name' => 'Licenciatura em Zootecnia'],
            ['id' => 10, 'code' => '10', 'name' => 'Licenciatura em Química'],
            ['id' => 11, 'code' => '11', 'name' => 'Licenciatura em Física'],
            ['id' => 12, 'code' => '12', 'name' => 'Licenciatura em Informática'],
            ['id' => 13, 'code' => '13', 'name' => 'Licenciatura em Biologia'],
            ['id' => 14, 'code' => '14', 'name' => 'Licenciatura em Matemática'],
            ['id' => 15, 'code' => '15', 'name' => 'Licenciatura em Ambiente e Sustentabilidade Comunitária'],
            ['id' => 16, 'code' => '15', 'name' => 'Licenciatura em Geografia'],
            ['id' => 17, 'code' => '15', 'name' => 'Licenciatura em Educação Visual'],
            ['id' => 18, 'code' => '15', 'name' => 'Licenciatura em Gestão de Recursos Humanos'],
            ['id' => 19, 'code' => '15', 'name' => 'Licenciatura em Contabilidade'],
            ['id' => 20, 'code' => '15', 'name' => 'Licenciatura em Gestão Pública e Autárquica'],
            ['id' => 21, 'code' => '15', 'name' => 'Licenciatura em Gestão Aduaneira'],
            ['id' => 22, 'code' => '15', 'name' => 'Licenciatura em Administração Pública'],
            ['id' => 23, 'code' => '15', 'name' => 'Licenciatura em Produção Agrícola'],
            ['id' => 24, 'code' => '15', 'name' => 'Licenciatura em Tecnologia de Alimentos'],
        ];

        foreach ($courses as $course) {
            DB::table('courses')->insert([
                'id' => $course['id'],
                'uuid' => Str::uuid()->toString(),
                'code' => $course['code'],
                'name' => $course['name'],
            ]);
        }
    }
}