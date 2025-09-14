<?php

namespace App\Console\Commands\ElasticSearch;

use App\Models\Application;
use App\Models\ApplicationTopCategoryPosition;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use App\Services\ElasticsearchService\ElasticsearchServiceInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApplicationTopPositionMappingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:mapping_top_position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mapping ApplicationTopCategoryPosition';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {


            $service = app()->make(ElasticsearchServiceInterface::class);

            $mapping = [
                'mappings' => [
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'applicationId' => ['type' => 'integer'],
                        'countryId' => ['type' => 'integer'],
                        'categoryId' => ['type' => 'integer'],
                        'position' => ['type' => 'integer'],
                        'date' => ['type' => 'date'],
                    ]
                ]
            ];

            $service->createIndex('application_top_position', $mapping);

        }
        catch (\Throwable $e) {
            Log::error('Error in elasticsearch:mapping_top_position', ['exception' => $e]);
            throw $e;
        }

    }
}
