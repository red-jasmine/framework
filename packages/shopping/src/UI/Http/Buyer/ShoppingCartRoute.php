<?php

namespace RedJasmine\Shopping\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers\ShoppingCartController;

class ShoppingCartRoute
{


    public static function api() : void
    {

        Route::prefix('shopping-cart')
             ->middleware(['auth:user'])
             ->group(function () {
                 // 获取当前用户购物车
                 Route::get('/', [ShoppingCartController::class, 'show']);
                 // 重新计算金额
                 Route::post('/calculate-amount', [ShoppingCartController::class, 'calculateAmount']);
                 // 添加商品到购物车
                 Route::post('/products', [ShoppingCartController::class, 'add']);
                 // 获取购物车商品列表
                 Route::get('/products', [ShoppingCartController::class, 'products']);
                 Route::delete('/products/{id}', [ShoppingCartController::class, 'destroy']);
                 Route::put('/products/{id}', [ShoppingCartController::class, 'updateQuantity']);
                 Route::post('/products/{id}/selected', [ShoppingCartController::class, 'selected']);

             });


    }
}