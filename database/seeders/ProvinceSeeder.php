<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Fetching Prrovince');
        $provinces = Http::timeout(30)->retry(3, 1000)
            ->get('https://wilayah.id/api/provinces.json')->json('data');
        foreach ($provinces as $province) {
            Region::create([
                'code' => data_get($province, 'code'),
                'name' => data_get($province, 'name'),
                'type' => 'province',
                'parent_code' => null
            ]);
        }
    }
}
