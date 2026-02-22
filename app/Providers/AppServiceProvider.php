<?php

namespace App\Providers;

use App\Infrastructure\Persistence\Models\ProductPrice;
use App\Infrastructure\Persistence\Models\StockMovement;

use App\Observers\ProductPriceObserver;
use App\Observers\StockMovementObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Model::preventLazyLoading(true);
        RepositoryServiceProvider::class;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        StockMovement::observe(StockMovementObserver::class);
        ProductPrice::observe(ProductPriceObserver::class);
    }
}
