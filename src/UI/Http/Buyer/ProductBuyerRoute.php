<?php

namespace RedJasmine\Product\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Buyer\Api\Controllers\SellerCategoryController;
use RedJasmine\Product\UI\Http\Buyer\Api\Controllers\BrandController;
use RedJasmine\Product\UI\Http\Buyer\Api\Controllers\ProductController;

class ProductBuyerRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'product' ], function () {

            Route::apiResource('brands', BrandController::class)->only([ 'index', 'show' ])->names('buyer.product.brands');


            Route::get('seller-categories/tree', [ SellerCategoryController::class, 'tree' ])->name('buyer.product.seller-categories.tree');
            Route::apiResource('seller-categories', SellerCategoryController::class)->only([ 'index', 'show' ])->names('buyer.product.seller-categories');

            Route::apiResource('products', ProductController::class)->only([ 'index', 'show' ])->names('buyer.product.products');

        });
    }
}
