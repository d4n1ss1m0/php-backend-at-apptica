<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\ApplicationTopCategoryPosition;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchApplicationTopCategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app-top-category:fetch';

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
        $service = app()->make(ApplicationPositionApiServiceInterface::class);

        $country = 1;
        $date = Carbon::now();

        $applications = Application::all();

        $insertData = [];

        foreach ($applications as $application) {
            $result = $service->getTopPositionForApp(
                $application->app_id,
                $country,
                Carbon::now()
            );

            foreach ($result as $date => $value) {
                foreach ($value as $categoryId => $position) {
                    $insertData[] = [
                        'application_id' => $application->id,
                        'country_id' => $country,
                        'category_id' => $categoryId,
                        'position' => $position,
                        'date' => $date,
                    ];
                }
            }
        }

        ApplicationTopCategoryPosition::query()->upsert($insertData, ['application_id', 'country_id', 'category_id', 'date'], ['position']);
    }
}
