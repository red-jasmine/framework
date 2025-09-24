<?php

namespace RedJasmine\Product\UI\Http\Owner;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\SeriesController;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\SkuController;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\ProductController;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\PropertyController;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\PropertyGroupController;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\PropertyValueController;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\BrandController;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\CategoryController;
use RedJasmine\Product\UI\Http\Owner\Api\Controllers\GroupController;

class ProductOwnerRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'product' ], function () {

            Route::apiResource('brands', BrandController::class)->only([ 'index', 'show' ])->names('seller.product.brands.index');


            Route::apiResource('property/properties', PropertyController::class)->only([ 'index', 'show' ])->names('seller.product.property.properties');
            Route::apiResource('property/values', PropertyValueController::class)->only([ 'index', 'show' ])->names('seller.product.property.values');
            Route::apiResource('property/groups', PropertyGroupController::class)->only([ 'index', 'show' ])->names('seller.product.property.groups');


            Route::get('categories/tree', [ CategoryController::class, 'tree' ])->name('seller.product.categories.tree');
            Route::apiResource('categories', CategoryController::class)->only([ 'show', 'index' ])->names('seller.product.categories');


            Route::get('groups/tree', [ GroupController::class, 'tree' ])->name('seller.product.groups.tree');
            Route::apiResource('groups', GroupController::class)->names('seller.product.groups');

            Route::apiResource('products', ProductController::class)->names('seller.product.products');


            Route::get('skus/logs', [ SkuController::class, 'logs' ])->name('seller.product.skus.logs');
            Route::post('skus/{id}', [ SkuController::class, 'action' ])->name('seller.product.skus.action');
            Route::apiResource('skus', SkuController::class)->only([ 'index', 'show' ])->names('seller.product.skus');

            Route::apiResource('series', SeriesController::class)->names('seller.product.series');

        });
    }
}
