<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = [
            ['name' => 'Instituto Superior de Contabilidade e Auditoria de Moçambique', 'province_id' => 1, 'priority_level' => 2],
            ['name' => 'Universidade Save, Campus Universitário de Venhene', 'province_id' => 2, 'priority_level' => 1],
            ['name' => 'Universidade Save, Campus Universitário 3 (FACSAD) – Maxixe', 'province_id' => 3, 'priority_level' => 1],
            ['name' => 'Universidade Save, Campus Universitário de Massinga', 'province_id' => 3, 'priority_level' => 1],
            ['name' => 'Universidade Licungo – Beira', 'province_id' => 4, 'priority_level' => 2],
            ['name' => 'Universidade Licungo – Quelimane', 'province_id' => 8, 'priority_level' => 2],
            ['name' => 'Universidade Púnguè: Campus Heróis Moçambicanos – Chimoio', 'province_id' => 5, 'priority_level' => 2],
            ['name' => 'Universidade Púnguè: Campus Universitário de Cambinde – Tete', 'province_id' => 6, 'priority_level' => 2],
            ['name' => 'Universidade Rovuma: Campus Universitário de Nampine – Nampula', 'province_id' => 7, 'priority_level' => 2],
            ['name' => 'Universidade Rovuma: Centro de Recursos da UniRovuma – Pemba', 'province_id' => 9, 'priority_level' => 2],
            ['name' => 'Universidade Rovuma: Campus Universitário de Chiuala – Lichinga', 'province_id' => 10, 'priority_level' => 2],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->insert($school);
        }
    }
}
