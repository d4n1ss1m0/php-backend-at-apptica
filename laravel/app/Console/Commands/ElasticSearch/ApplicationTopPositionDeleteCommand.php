<?php

namespace App\Console\Commands\ElasticSearch;

use App\Models\Application;
use App\Models\ApplicationTopCategoryPosition;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use App\Services\ElasticsearchService\ElasticsearchServiceInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ApplicationTopPositionDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:delete_top_position {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from api apptica';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app()->make(ElasticsearchServiceInterface::class);
        $id = $this->argument('id');

        $res = $service->deleteDocument('application_top_position', $id);

        $this->info(json_encode($res, JSON_PRETTY_PRINT));
    }
}
