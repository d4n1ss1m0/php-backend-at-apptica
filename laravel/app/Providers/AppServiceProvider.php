<?php

namespace App\Providers;

use App\Services\ApplicationPositionApiService\ApplicationPositionApiService;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use App\Services\ApplicationPositionCreateService\ApplicationPositionCreateService;
use App\Services\ApplicationPositionCreateService\ApplicationPositionCreateServiceInterface;
use App\Services\ApplicationPositionSearchService\ApplicationPositionSearchService;
use App\Services\ApplicationPositionSearchService\ApplicationPositionSearchServiceInterface;
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
        $this->app->bind(ApplicationPositionSearchServiceInterface::class, ApplicationPositionSearchService::class);
        $this->app->bind(ApplicationPositionCreateServiceInterface::class, ApplicationPositionCreateService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
