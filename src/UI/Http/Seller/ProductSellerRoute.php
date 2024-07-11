<?php

namespace RedJasmine\Product\UI\Http\Seller;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\ProductController;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\PropertyController;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\PropertyGroupController;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\PropertyValueController;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\BrandController;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\CategoryController;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\SellerCategoryController;

class ProductSellerRoute
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


            Route::get('seller-categories/tree', [ SellerCategoryController::class, 'tree' ])->name('seller.product.seller-categories.tree');
            Route::apiResource('seller-categories', SellerCategoryController::class)->names('seller.product.seller-categories');

            Route::apiResource('products', ProductController::class)->names('seller.product.products');
        });
    }
}
