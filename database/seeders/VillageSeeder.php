<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        $districts = Region::where('type', 'district')->get();

        foreach ($districts as $district) {
            $this->command->info("Fetching villages for {$district->name}...");

            try {
                $response = Http::timeout(30)->retry(3, 1000)
                    ->get("https://wilayah.id/api/villages/{$district->code}.json");

                if (!$response->successful()) {
                    $this->command->warn("❌ Gagal fetch dari {$district->name} ({$district->code}), status: " . $response->status());
                    continue;
                }

                $villages = $response->json('data');

                foreach ($villages as $village) {
                    // CEK DULU apakah kode desa sudah ada
                    $existing = Region::where('code', data_get($village, 'code'))->exists();
                    if (!$existing) {
                        Region::create([
                            'code' => data_get($village, 'code'),
                            'name' => data_get($village, 'name'),
                            'type' => 'village',
                            'postal_code' => data_get($village, 'postal_code'),
                            'parent_code' => $district->code
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $this->command->warn("❌ Error: {$e->getMessage()} saat fetch desa dari {$district->name}. Lewatkan...");
                continue;
            }
        }
    }
}
