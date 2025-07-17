<?php

namespace RedJasmine\Shopping\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Address\UI\Http\User\AddressUserRoute;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers\OrderController;
use RedJasmine\Wallet\UI\Http\User\WalletUserRoute;

class ShoppingBuyerRoute
{


    public static function api() : void
    {
        Route::middleware('auth:user')->group(function () {

            Route::prefix('shopping')
                 ->group(function () {

                     Route::prefix('order')->group(function () {
                         Route::post('check', [OrderController::class, 'check']);
                         Route::post('buy', [OrderController::class, 'buy']);


                     });


                 });


            ShoppingCartRoute::api();

            AddressUserRoute::api();


            WalletUserRoute::api();

        });


    }
}
