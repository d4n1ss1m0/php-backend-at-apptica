<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationTopCategoryPosition;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationTopCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

//        dd($insertData);


        try {
            ApplicationTopCategoryPosition::query()->upsert($insertData, ['application_id', 'country_id', 'category_id', 'date'], ['position']);
        } catch (\Exception $e) {
            dd($insertData);
        }
    }
}
