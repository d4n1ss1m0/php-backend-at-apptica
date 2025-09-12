<?php

namespace App\Services\ApplicationPositionSearchService;

use App\Services\ElasticsearchService\ElasticsearchServiceInterface;
use Carbon\Carbon;

class ApplicationPositionSearchService implements ApplicationPositionSearchServiceInterface
{
    private array $searchParams = [];
    public function __construct(private readonly ElasticsearchServiceInterface $elasticsearchService)
    {

    }

    public function getSearchParams(): array
    {
        return $this->searchParams;
    }


    public function addDateFilter(Carbon $date)
    {
        $this->searchParams['query']['bool']['must'][] = [
            'match' => [
                'date' => $date->format('Y-m-d')
            ]
        ];
    }

    public function search()
    {
        $response = $this->elasticsearchService->search(env('ELASTIC_INDEX'), $this->getSearchParams());

        $data = $response['hits']['hits'];
        $data = array_column($data, '_source');

        $result = [];
        foreach ($data as $item) {
            $result[$item['categoryId']] = $item['position'];
        }

        return $result;
    }
}
