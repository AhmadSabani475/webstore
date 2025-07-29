<?php
declare(strict_type=1);
namespace App\Services;

use App\Models\Region;
use App\Data\RegionData;
use Spatie\LaravelData\DataCollection;

class RegionQueryService
{
    public function searchRegionByName(string $keyword, int $limit = 5): DataCollection
    {
        $regions = Region::where('type', 'village')
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('postal_code', 'LIKE', "%$keyword%")
                    ->orWhereHas('parent', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%$keyword%");
                    })
                    ->orWhereHas('parent.parent', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%$keyword%");
                    })
                    ->orWhereHas('parent.parent.parent', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%$keyword%");
                    });
            })->with(['parent.parent.parent'])->limit($limit)->get();
        return new DataCollection(RegionData::class, $regions);
    }

    public function searchRegionByCode(string $code): RegionData
    {
        $region = Region::where('code', $code)
            ->with(['parent.parent.parent'])
            ->first();

        if (!$region) {
            // Bisa return dummy data atau throw exception
            return new RegionData(
                code: $code,
                province: '-',
                city: '-',
                district: '-',
                sub_district: '-',
                postal_code: '-', // hindari null
            );
        }

        return RegionData::fromModel($region);
    }
}