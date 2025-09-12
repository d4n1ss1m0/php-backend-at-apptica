<?php

namespace App\Services\ApplicationPositionCreateService;

use App\Models\Application;
use Carbon\Carbon;

interface ApplicationPositionCreateServiceInterface
{
    public function addApplicationPositionsByDate(Application $application, Carbon $date, int $countryId):array;
    public function getInsertDataFromApi(Application $application, Carbon $searchDate, int $countryId): array;
}
