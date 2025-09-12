<?php

namespace App\Services\ApplicationPositionApiService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ApplicationPositionApiService implements ApplicationPositionApiServiceInterface
{

    public function getTopPositionForApp(int $appId, int $countryId, Carbon $date): array
    {
        $url = env('APPLICATION_TOP_CATEGORY_API') . "/package/top_history/{$appId}/{$countryId}";
        $response = Http::get($url, [
            'date_from' => $date->format('Y-m-d'),
            'date_to' => $date->format('Y-m-d'),
            env('APPLICATION_TOP_CATEGORY_TOKEN_NAME') => env('APPLICATION_TOP_CATEGORY_TOKEN'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $this->transformDataFromApi($data['data']);
        }

        if($response->getStatusCode() === 402) {
            throw new \InvalidArgumentException('Top History info is forbidden');
        }

        throw new \Exception('Something went wrong');
    }

    public function getTopPositionForAppWithDateInterval(int $appId, int $countryId, Carbon $dateFrom, Carbon $dateTo): array
    {
        $url = env('APPLICATION_TOP_CATEGORY_API') . "/package/top_history/{$appId}/{$countryId}";
        $response = Http::get($url, [
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d'),
            env('APPLICATION_TOP_CATEGORY_TOKEN_NAME') => env('APPLICATION_TOP_CATEGORY_TOKEN'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $this->transformDataFromApi($data['data']);
        }

        throw new \Exception('Something went wrong');
    }

    private function transformDataFromApi(array $data): array
    {
        $returnArray = [];

        foreach ($data as $categoryId => $subcategories) {
            $positions = [];
            foreach ($subcategories as $subcategoryId => $dates) {
                foreach ($dates as $date => $position) {
                    $positions[$date][] = $position;
                }
            }


            foreach ($positions as $date => $valuesPerDate) {
                $positionsArray = array_filter($valuesPerDate, fn($v) => $v !== null);;
                $minPosition = count($positionsArray) ? min($positionsArray) : null;

                $returnArray[$date][$categoryId] = $minPosition;
            }

        }

        return $returnArray;
    }
}
