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
        $dateFrom = Carbon::now()->subDays(30);
        $dateTo = Carbon::now();

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
            ApplicationTopCategoryPosition::query()->upsert($insertData, ['date', 'category_id'], ['position']);
        } catch (\Exception $e) {
            dd($insertData);
        }
    }
}
