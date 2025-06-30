<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\ShoppingCart\UI\Http\Controllers\ShoppingCartController;

Route::group(['prefix' => 'shopping-cart', 'middleware' => ['auth:sanctum']], function () {
    // 获取当前用户购物车
    Route::get('/', [ShoppingCartController::class, 'show']);
    
    // 获取购物车商品列表
    Route::get('/products', [ShoppingCartController::class, 'products']);
    
    // 添加商品到购物车
    Route::post('/add', [ShoppingCartController::class, 'add']);
    
    // 移除商品
    Route::delete('/remove/{productId}', [ShoppingCartController::class, 'remove']);
    
    // 更新商品数量
    Route::put('/update-quantity/{productId}', [ShoppingCartController::class, 'updateQuantity']);
    
    // 选择/取消选择商品
    Route::post('/select-products', [ShoppingCartController::class, 'selectProducts']);
    
    // 重新计算金额
    Route::post('/calculate-amount', [ShoppingCartController::class, 'calculateAmount']);
}); 