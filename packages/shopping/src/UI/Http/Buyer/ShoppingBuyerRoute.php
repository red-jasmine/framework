<?php

namespace RedJasmine\Shopping\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers\OrderController;

class ShoppingBuyerRoute
{


    public static function api()
    {

        Route::prefix('shopping')
             ->middleware(['auth:user'])
             ->group(function () {

                 Route::prefix('order')->group(function () {
                     Route::post('check', [OrderController::class, 'check']);
                     Route::post('buy', [OrderController::class, 'buy']);


                 });


             });

        ShoppingCartRoute::api();


    }
}
