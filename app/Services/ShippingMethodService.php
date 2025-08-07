<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\CartData;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Data\ShippingServiceData;
use Spatie\LaravelData\DataCollection;
use App\Contract\ShippingDriverInterface;
use App\Drivers\Shipping\APIKurirShippingDriver;
use App\Drivers\Shipping\OfflineShippingDriver;
use Illuminate\Support\Facades\Cache;

class ShippingMethodService
{
    protected array $drivers;
    public function __construct()
    {
        $this->drivers = [
            new OfflineShippingDriver(),
            new APIKurirShippingDriver()
        ];
    }

    public function getDriver(ShippingServiceData $service): ShippingDriverInterface
    {
        return collect($this->drivers)
            ->first(fn(ShippingDriverInterface $shipping_driver) => $shipping_driver->driver === $service->driver);
    }

    public function getShippingServices(): DataCollection
    {
        return collect($this->drivers)->flatMap(fn(ShippingDriverInterface $driver) => $driver->getServices()->toCollection())
            ->pipe(fn($items) => ShippingServiceData::collect($items, DataCollection::class));
    }
    public function getShippingMethods(
        RegionData $origin,
        RegionData $destination,
        CartData $cart
    ): DataCollection {
        return $this->getShippingServices()
            ->toCollection()->map(function (ShippingServiceData $shippingService) use ($origin, $destination, $cart) {
                $shipping_data = $this->getDriver($shippingService)
                    ->getRate($origin, $destination, $cart, $shippingService);
                if ($shipping_data == null) {
                    return;
                }
                Cache::put(
                    key: "shipping_data:{$shipping_data->hash}",
                    value: $shipping_data,
                    ttl: now()->addMinutes(15)
                );
                return $shipping_data;
            })
            ->reject(fn($item) => is_null($item))
            ->pipe(fn($items) => ShippingData::collect($items, DataCollection::class));
    }
    public function getShippingMethod(
        string $hash
    ): ?ShippingData {
        return Cache::get("shipping_data:{$hash}");
    }
}
