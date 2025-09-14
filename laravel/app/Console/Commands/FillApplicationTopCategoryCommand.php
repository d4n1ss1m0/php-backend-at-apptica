<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\ApplicationTopCategoryPosition;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FillApplicationTopCategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app-top-category:fill';

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

        //такой интервал нужен для того, чтобы протестировать запрос к предоставленному api при использовании поиска
        $dateFrom = Carbon::now()->subDays(30);
        $dateTo = Carbon::now()->subDays(10);

        $applications = Application::all();

        $insertData = [];


        foreach ($applications as $application) {
            $result = $service->getTopPositionForAppWithDateInterval(
                $application->app_id,
                $country,
                $dateFrom,
                $dateTo
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


        try {
            ApplicationTopCategoryPosition::query()->upsert($insertData, ['application_id', 'country_id', 'category_id', 'date'], ['position']);
            $this->call('elasticsearch:mapping_top_position');
            $this->call('elasticsearch:all_add_top_position');
        } catch (\Exception $e) {
            Log::error('Error in app-top-category:fill', ['exception' => $e]);
            throw $e;
        }
    }
}
