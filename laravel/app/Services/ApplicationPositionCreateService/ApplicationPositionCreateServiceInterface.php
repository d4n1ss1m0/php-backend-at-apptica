<?php

namespace App\Services\ApplicationPositionCreateService;

use App\Models\Application;
use Carbon\Carbon;

interface ApplicationPositionCreateServiceInterface
{
    /**
     * Создать запись в бд
     *
     * @param Application $application
     * @param Carbon $date
     * @param int $countryId
     * @return array
     */
    public function addApplicationPositionsByDate(Application $application, Carbon $date, int $countryId):array;

    /**
     * Получить данные для вставки в БД
     *
     * @param Application $application
     * @param Carbon $searchDate
     * @param int $countryId
     * @return array
     */
    public function getInsertDataFromApi(Application $application, Carbon $searchDate, int $countryId): array;
}
