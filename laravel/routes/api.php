<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('appTopCategory', [\App\Http\Controllers\ApplicationPositionsSearchController\ApplicationPositionsSearchController::class, 'search'])
    ->middleware(\App\Http\Middleware\RateLimitByIp::class);
