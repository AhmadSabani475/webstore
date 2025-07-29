<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regencies = Region::where('type', 'regency')->get();
        foreach ($regencies as $regency) {
            $this->command->info("fetching District for {$regency['name']}...");
            $districts = Http::timeout(30)->retry(3, 1000)->get("https://wilayah.id/api/districts/{$regency['code']}.json")->json('data');
            foreach ($districts as $district) {
                Region::create([
                    'code' => data_get($district, 'code'),
                    'name' => data_get($district, 'name'),
                    'type' => 'district',
                    'parent_code' => data_get($regency, 'code')
                ]);
            }
        }
    }
}
