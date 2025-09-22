<?php

namespace RedJasmine\ShoppingCart\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Controllers\ShoppingCartController;

class ShoppingCartRoute
{
    public static function api() : void
    {
        Route::prefix('shopping-cart')
            ->middleware(['auth:user'])
            ->group(function () {
                Route::get('/', [ShoppingCartController::class, 'show']);
                Route::post('/calculate-amount', [ShoppingCartController::class, 'calculateAmount']);
                Route::post('/products', [ShoppingCartController::class, 'add']);
                Route::delete('/products/{id}', [ShoppingCartController::class, 'destroy']);
                Route::put('/products/{id}', [ShoppingCartController::class, 'updateQuantity']);
                Route::post('/products/{id}/selected', [ShoppingCartController::class, 'selected']);
            });
    }
}


