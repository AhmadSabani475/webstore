<?php
declare(strict_types=1);
namespace App\Data;

use App\Models\Region;
use Spatie\LaravelData\Data;
use Livewire\Attributes\Computed;

class RegionData extends Data
{
    #[Computed]
    public string $label;
    public function __construct(
        public string $code,
        public string $province,
        public string $city,
        public string $district,
        public string $sub_district,
        public ?string $postal_code,
        public string $country = 'indonesia',
    ) {
        $this->label = "$sub_district, $district, $city, $province, $postal_code";
    }
    public static function fromModel(Region $region): self
    {
        return new self(
            code: $region->code,
            province: $region->parent?->parent?->parent?->name ?? 'Unknown',
            city: $region->parent?->parent?->name ?? 'Unknown',
            district: $region->parent?->name ?? 'Unknown',
            sub_district: $region->name,
            postal_code: $region->postal_code,
        );
    }
}
