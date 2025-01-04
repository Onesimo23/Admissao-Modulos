<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = [
            ['id' => 1, 'name' => 'Maputo'],
            ['id' => 2, 'name' => 'Gaza'],
            ['id' => 3, 'name' => 'Inhambane'],
            ['id' => 4, 'name' => 'Sofala'],
            ['id' => 5, 'name' => 'Manica'],
            ['id' => 6, 'name' => 'Tete'],
            ['id' => 7, 'name' => 'Nampula'],
            ['id' => 8, 'name' => 'Zambezia'],
            ['id' => 9, 'name' => 'Cabo Delgado'],
            ['id' => 10, 'name' => 'Niassa'],
            ['id' => 11, 'name' => 'Inhambane - Maxixe'],
            ['id' => 12, 'name' => 'Inhambane - Massinga'],
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')
               ->updateOrInsert(
                   ['id' => $province['id']],
                   [
                       'uuid' => Str::uuid()->toString(),
                       'name' => $province['name'],
                   ]
               );
        }
    }
}