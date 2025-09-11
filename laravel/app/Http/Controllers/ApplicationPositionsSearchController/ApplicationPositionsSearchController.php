<?php

namespace App\Http\Controllers\ApplicationPositionsSearchController;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchApplicationPositionRequest;
use App\Traits\HttpResponseTrait;

class ApplicationPositionsSearchController extends Controller
{
    use HttpResponseTrait;
    public function search(SearchApplicationPositionRequest $request)
    {

    }
}
