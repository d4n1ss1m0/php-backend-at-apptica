<?php

namespace App\Services\ApplicationPositionApiService;

use Carbon\Carbon;

interface ApplicationPositionApiServiceInterface
{
    public function getTopPositionForApp(int $appId, int $countryId, Carbon $date): array;
    public function getTopPositionForAppWithDateInterval(int $appId, int $countryId, Carbon $dateFrom, Carbon $dateTo): array;
}
