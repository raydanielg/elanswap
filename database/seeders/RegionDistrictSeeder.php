<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionDistrictSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('region.json');
        if (!file_exists($path)) {
            return;
        }
        $json = json_decode(file_get_contents($path), true);
        if (!is_array($json)) {
            return;
        }

        // region.json has mixed structure; gather all region entries that have 'wilaya' children
        $regionsArrays = [];
        if (isset($json['mkoa']) && is_array($json['mkoa'])) {
            $regionsArrays[] = $json['mkoa'];
        }
        // Also consider top-level keys that may themselves be regions with 'wilaya'
        $regionsArrays[] = $json;

        $seen = [];
        foreach ($regionsArrays as $regionsBlock) {
            foreach ($regionsBlock as $regionName => $payload) {
                if (!is_array($payload) || !isset($payload['wilaya']) || !is_array($payload['wilaya'])) {
                    continue;
                }
                $rname = trim($regionName);
                if ($rname === '') continue;
                if (!isset($seen[$rname])) {
                    $region = Region::firstOrCreate(['name' => $rname]);
                    $seen[$rname] = $region->id;
                } else {
                    $region = Region::find($seen[$rname]);
                }

                foreach ($payload['wilaya'] as $districtName => $dData) {
                    $dname = trim($districtName);
                    if ($dname === '') continue;
                    District::firstOrCreate([
                        'region_id' => $region->id,
                        'name' => $dname,
                    ]);
                }
            }
        }
    }
}
