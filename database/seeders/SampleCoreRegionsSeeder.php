<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\District;

class SampleCoreRegionsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Mwanza' => [
                'Ilemela', 'Kwimba', 'Sengerema', 'Nyamagana', 'Magu', 'Ukerewe', 'Misungwi',
            ],
            'Dar es Salaam' => [
                'Ilala', 'Kinondoni', 'Temeke', 'Ubungo', 'Kigamboni',
            ],
            'Arusha' => [
                'Arusha City', 'Arusha', 'Karatu', 'Longido', 'Meru', 'Monduli', 'Ngorongoro',
            ],
        ];

        foreach ($data as $regionName => $districts) {
            $region = Region::firstOrCreate(['name' => $regionName]);
            foreach ($districts as $name) {
                District::firstOrCreate([
                    'region_id' => $region->id,
                    'name' => $name,
                ]);
            }
        }
    }
}
