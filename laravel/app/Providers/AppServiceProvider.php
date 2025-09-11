<?php

namespace App\Providers;

use App\Services\ApplicationPositionApiService\ApplicationPositionApiService;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use App\Services\ElasticsearchService\ElasticsearchService;
use App\Services\ElasticsearchService\ElasticsearchServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApplicationPositionApiServiceInterface::class, ApplicationPositionApiService::class);
        $this->app->bind(ElasticsearchServiceInterface::class, ElasticsearchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
