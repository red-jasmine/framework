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
                 // 添加商品到购物车
                 Route::post('/products', [ShoppingCartController::class, 'add']);
                 // 获取购物车商品列表
                 Route::get('/products', [ShoppingCartController::class, 'products']);

                 // 移除商品
                 Route::delete('/remove/{productId}', [ShoppingCartController::class, 'remove']);

                 // 更新商品数量
                 Route::put('/update-quantity/{productId}', [ShoppingCartController::class, 'updateQuantity']);

                 // 选择/取消选择商品
                 Route::post('/select-products', [ShoppingCartController::class, 'selectProducts']);

                 // 重新计算金额
                 Route::post('/calculate-amount', [ShoppingCartController::class, 'calculateAmount']);
             });


    }
}