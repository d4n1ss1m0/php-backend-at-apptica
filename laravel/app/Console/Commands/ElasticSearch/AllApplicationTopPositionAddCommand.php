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

class AllApplicationTopPositionAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:all_add_top_position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All add ApplicationTopCategoryPosition';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $service = app()->make(ElasticsearchServiceInterface::class);
            $index = 'application_top_position';
            $bulkData = [];
            foreach (ApplicationTopCategoryPosition::all() as $model) {
                $bulkData[] = $model->serializeForElastic();
            }

//        // Elasticsearch Bulk API
//        $payload = implode("\n", array_map(fn($d) => json_encode($d), $bulkData)) . "\n";
//        dd($bulkData);

            $service->bulkInsert($index, $bulkData);
        } catch (\Throwable $e) {
            Log::error('Error in elasticsearch:all_add_top_position', ['exception' => $e]);
            throw $e;
        }



    }
}
