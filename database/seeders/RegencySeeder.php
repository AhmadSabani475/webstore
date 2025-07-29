<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = Region::where('type', 'province')->get();
        foreach ($provinces as $province) {
            $this->command->info("fetching Regencies for {$province['name']}...");
            $regencies = Http::timeout(30)->retry(3, 1000)
                ->get("https://wilayah.id/api/regencies/{$province->code}.json")->json('data');
            foreach ($regencies as $regency) {
                Region::create([
                    'code' => data_get($regency, 'code'),
                    'name' => data_get($regency, 'name'),
                    'type' => 'regency',
                    'parent_code' => data_get($province, 'code')
                ]);
            }
        }


    }
}
