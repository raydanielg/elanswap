<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\District;

class SampleMwanzaSeeder extends Seeder
{
    public function run(): void
    {
        $regionName = 'Mwanza';
        $districts = [
            'Ilemela',
            'Kwimba',
            'Sengerema',
            'Nyamagana',
            'Magu',
            'Ukerewe',
            'Misungwi',
        ];

        $region = Region::firstOrCreate(['name' => $regionName]);

        foreach ($districts as $name) {
            District::firstOrCreate([
                'region_id' => $region->id,
                'name' => $name,
            ]);
        }
    }
}
