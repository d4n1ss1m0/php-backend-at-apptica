<?php

namespace App\Services\ApplicationPositionSearchService;

use Carbon\Carbon;

interface ApplicationPositionSearchServiceInterface
{
    /**
     * Добавить фильтр по дате
     *
     * @param Carbon $date
     * @return mixed
     */
    public function addDateFilter(Carbon $date);

    /**
     * Поиск
     *
     * @return mixed
     */
    public function search();
}
