<?php

namespace RedJasmine\Product\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\User\Api\Controllers\GroupController;
use RedJasmine\Product\UI\Http\User\Api\Controllers\BrandController;
use RedJasmine\Product\UI\Http\User\Api\Controllers\ProductController;
use RedJasmine\Product\UI\Http\User\Api\Controllers\SeriesController;

class ProductUserRoute
{

    public static function api() : void
    {
        Route::group(['prefix' => 'product'], function () {

            Route::apiResource('brands', BrandController::class)->only(['index', 'show'])->names('buyer.product.brands');


            Route::get('groups/tree', [GroupController::class, 'tree'])->name('buyer.product.groups.tree');
            Route::apiResource('groups', GroupController::class)->only(['index', 'show'])->names('buyer.product.groups');

            Route::apiResource('products', ProductController::class)->only(['index', 'show'])->names('buyer.product.products');
            Route::get('products/{id}/series', [SeriesController::class, 'show'])->name('buyer.product.products.series');

        });
    }
}
