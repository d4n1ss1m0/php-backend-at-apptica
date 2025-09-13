<?php

namespace App\Services\ApplicationPositionApiService;

use Carbon\Carbon;

interface ApplicationPositionApiServiceInterface
{
    /**
     * Получить позиции в топе для приложения для конкретной даты
     *
     * @param int $appId - айди приложения из БД
     * @param int $countryId - айди страны
     * @param Carbon $date - дата
     * @return array
     * @throws \InvalidArgumentException - Top History info is forbidden
     * @throws \Exception - Something went wrong
     */
    public function getTopPositionForApp(int $appId, int $countryId, Carbon $date): array;

    /**
     * Получить позиции в топе для приложения для временного интервала
     *
     * @param int $appId - айди приложения из БД
     * @param int $countryId - айди страны
     * @param Carbon $dateFrom - начальная дата
     * @param Carbon $dateTo - конечная дата
     * @return array
     * @throws \InvalidArgumentException - Top History info is forbidden
     * @throws \Exception - Something went wrong
     */
    public function getTopPositionForAppWithDateInterval(int $appId, int $countryId, Carbon $dateFrom, Carbon $dateTo): array;
}
