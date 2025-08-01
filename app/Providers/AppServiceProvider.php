<?php

namespace App\Providers;

use App\Models\User;
use App\Services\RegionQueryService;
use Illuminate\Support\Number;
use App\Actions\ValidateCartStock;
use App\Services\SessionCartService;
use Illuminate\Support\Facades\Gate;
use App\Contract\CartServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CartServiceInterface::class, SessionCartService::class);
        $this->app->bind(RegionQueryService::class, RegionQueryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Model::unguard();
        Number::useCurrency('IDR');

        Gate::define('is_stock_available', function (User $user = null) {
            try {
                ValidateCartStock::run();
                return true;
            } catch (ValidationException $e) {
                session()->falsh('error', $e->getMessage());
                return false;
            }
        });
    }
}
