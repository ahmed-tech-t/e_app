<?php

namespace App\Providers;

use App\Domain\Repo\CategoryRepo;
use App\Domain\Repo\ProductRepo;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\repo\ECategoryRepo;
use App\Infrastructure\Persistence\repo\EProductRepo;
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
        //
    }
}
