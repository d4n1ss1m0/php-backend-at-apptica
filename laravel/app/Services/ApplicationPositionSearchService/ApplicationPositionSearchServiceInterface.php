<?php

namespace App\Services\ApplicationPositionSearchService;

use Carbon\Carbon;

interface ApplicationPositionSearchServiceInterface
{
    public function addDateFilter(Carbon $date);

    public function search();
}
