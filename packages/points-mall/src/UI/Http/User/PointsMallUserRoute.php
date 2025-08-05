<?php

namespace RedJasmine\PointsMall\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\PointsMall\UI\Http\User\Api\Controllers\PointsExchangeOrderController;
use RedJasmine\PointsMall\UI\Http\User\Api\Controllers\PointsProductController;
use RedJasmine\PointsMall\UI\Http\User\Api\Controllers\PointProductCategoryController;

class PointsMallUserRoute
{
    public static function api() : void
    {
        Route::group(['prefix' => 'points-mall'], function () {
            // 积分商品分类
            Route::get('categories/tree', [PointProductCategoryController::class, 'tree']);

            Route::apiResource('categories', PointProductCategoryController::class)->only(['index', 'show']);

            // 积分商品

            Route::apiResource('products', PointsProductController::class)->only(['index', 'show']);

            Route::post('orders/exchange', [PointsExchangeOrderController::class, 'exchange']);
            Route::post('orders/{id}/pay', [PointsExchangeOrderController::class, 'pay']);
            Route::apiResource('orders', PointsExchangeOrderController::class)->only(['index', 'show']);
        });
    }

    public static function web() : void
    {

    }
}