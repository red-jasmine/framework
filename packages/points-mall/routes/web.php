<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\PointsMall\UI\Http\Controllers\PointsMallController;
use RedJasmine\PointsMall\UI\Http\Controllers\PointsExchangeOrderController;

Route::group(['prefix' => 'points-mall'], function () {
    Route::get('products', [PointsMallController::class, 'index']);
    Route::get('products/{id}', [PointsMallController::class, 'show']);
    Route::get('orders', [PointsExchangeOrderController::class, 'index']);
}); 