<?php

namespace App\Services\ApplicationPositionCreateService;

use App\Jobs\AddApplicationTopPositionToElasticsearch;
use App\Models\Application;
use App\Models\ApplicationTopCategoryPosition;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use App\Services\ElasticsearchService\ElasticsearchServiceInterface;
use Carbon\Carbon;

class ApplicationPositionCreateService implements ApplicationPositionCreateServiceInterface
{
    public function __construct(private readonly ApplicationPositionApiServiceInterface $apiService, private readonly ElasticsearchServiceInterface $elasticsearchService)
    {
    }

    public function addApplicationPositionsByDate(Application $application, Carbon $date, int $countryId):array
    {
        $insertData = $this->getInsertDataFromApi($application, $date, $countryId);

        ApplicationTopCategoryPosition::query()->upsert($insertData, ['application_id', 'country_id','date', 'category_id'], ['position']);

        $items = ApplicationTopCategoryPosition::query()
            ->where('application_id', $application->id)
            ->where('date', $date->format('Y-m-d'))
            ->get();

        foreach ($items as $item) {
            $data = $item->serializeForElastic();
            $this->elasticsearchService->addDocument('application_top_position', $data, $data['id']);
        }

        $result = [];

        foreach ($insertData as $item) {
            $result[(string)$item['category_id']] = $item['position'];
        }

        return $result;
    }


    public function getInsertDataFromApi(Application $application, Carbon $searchDate, int $countryId): array
    {
        $result = $this->apiService->getTopPositionForApp(
            $application->app_id,
            $countryId,
            $searchDate
        );

        $insertData = [];

        foreach ($result as $date => $value) {
            foreach ($value as $categoryId => $position) {
                $insertData[] = [
                    'application_id' => $application->id,
                    'country_id' => $countryId,
                    'category_id' => $categoryId,
                    'position' => $position,
                    'date' => $date,
                ];
            }
        }

        return $insertData;
    }
}
