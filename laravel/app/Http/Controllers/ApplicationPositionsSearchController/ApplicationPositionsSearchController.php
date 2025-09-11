<?php

namespace App\Http\Controllers\ApplicationPositionsSearchController;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchApplicationPositionRequest;
use App\Models\Application;
use App\Services\ApplicationPositionApiService\ApplicationPositionApiServiceInterface;
use App\Services\ApplicationPositionCreateService\ApplicationPositionCreateServiceInterface;
use App\Services\ApplicationPositionSearchService\ApplicationPositionSearchServiceInterface;
use App\Traits\HttpResponseTrait;
use Carbon\Carbon;

class ApplicationPositionsSearchController extends Controller
{
    use HttpResponseTrait;
    public function __construct(private readonly ApplicationPositionSearchServiceInterface $applicationPositionSearchService,
                                private readonly ApplicationPositionCreateServiceInterface $applicationPositionCreateService)
    {
    }

    public function search(SearchApplicationPositionRequest $request)
    {
        try {
            $date = Carbon::parse($request->get('date'));
            //добавляю фильтр по дате
            $this->applicationPositionSearchService->addDateFilter($date);
            //поиск
            $response = $this->applicationPositionSearchService->search();

            //если в эластике ничего нет
            if(empty($response)) {
                //поскольку у нас только одно приложение, явно его ищем
                $application = Application::query()->find(1);
                //добавляем в бд новые записи выбранной даты
                $response = $this->applicationPositionCreateService->addApplicationPositionsByDate($application, $date, 1);
            }

            return $this->success($response);
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 'forbidden', 402);
        }
        catch (\Exception $e) {
            return $this->error('unexpected error');
        }
    }
}
