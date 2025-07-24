<?php

namespace RedJasmine\PointsMall\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\PointsMall\UI\Http\Admin\Api\Controllers\PointsProductController;
use RedJasmine\PointsMall\UI\Http\Admin\Api\Controllers\PointProductCategoryController;

class PointsMallAdminRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'points-mall'], function () {
            // 积分商品分类管理
            Route::get('categories/tree', [PointProductCategoryController::class, 'tree']);
            Route::apiResource('categories', PointProductCategoryController::class);
            // 积分商品管理
            Route::group(['prefix' => 'products'], function () {
                Route::apiResource('products', PointsProductController::class);
                
                // 业务操作
                Route::patch('products/{id}/publish', [PointsProductController::class, 'publish']);
                Route::patch('products/{id}/off-sale', [PointsProductController::class, 'offSale']);
                
                // 批量操作
                Route::post('products/batch-publish', [PointsProductController::class, 'batchPublish']);
                Route::post('products/batch-off-sale', [PointsProductController::class, 'batchOffSale']);
                
                // 统计信息
                Route::get('products/statistics', [PointsProductController::class, 'statistics']);
            });
        });
    }

    public static function web(): void
    {

    }
} 