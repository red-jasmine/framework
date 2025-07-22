<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\PointsMall\UI\Http\Controllers\PointsMallController;
use RedJasmine\PointsMall\UI\Http\Controllers\PointsExchangeOrderController;

Route::group(['prefix' => 'points-mall'], function () {
    // 商品相关
    Route::get('products', [PointsMallController::class, 'index']);
    Route::get('products/{id}', [PointsMallController::class, 'show']);
    Route::get('products/category/{categoryId}', [PointsMallController::class, 'productsByCategory']);
    Route::get('products/search', [PointsMallController::class, 'search']);
    
    // 兑换相关
    Route::post('exchange', [PointsMallController::class, 'exchange']);
    Route::post('check-exchange', [PointsMallController::class, 'checkExchange']);
    
    // 订单相关
    Route::get('orders', [PointsExchangeOrderController::class, 'index']);
    Route::get('orders/{id}', [PointsExchangeOrderController::class, 'show']);
    Route::get('orders/statistics', [PointsExchangeOrderController::class, 'statistics']);
}); 