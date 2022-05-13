<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $dataDir = database_path('data') . DIRECTORY_SEPARATOR;
        DB::table('countries')->insert(json_decode(file_get_contents($dataDir . 'countries.json'), true));
        DB::table('provinces')->insert(json_decode(file_get_contents($dataDir . 'provinces.json'), true));
        DB::table('cities')->insert(json_decode(file_get_contents($dataDir . 'cities.json'), true));
        $this->chunkInsert('areas', $dataDir . 'areas.json');
        $this->chunkInsert('streets', $dataDir . 'streets.json');
        $this->chunkInsert('villages', $dataDir . 'villages.json');
    }

    protected function chunkInsert(string $table, string $filename): void
    {
        foreach (array_chunk(json_decode(file_get_contents($filename), true), 500) as $item) {
            DB::table($table)->insert($item);
        }
    }
}
