<?php

namespace RedJasmine\Shopping\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Address\UI\Http\User\AddressUserRoute;
use RedJasmine\Order\UI\Http\User\Api\OrderUserApiRoute;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers\OrderController;
use RedJasmine\Wallet\UI\Http\User\WalletUserRoute;

class ShoppingBuyerRoute
{


    public static function api() : void
    {
        Route::middleware('auth:api')->group(function () {

            Route::prefix('shopping')
                 ->group(function () {

                     Route::prefix('order')->group(function () {
                         Route::post('check', [OrderController::class, 'check']);
                         Route::post('buy', [OrderController::class, 'buy']);
                         Route::post('pay/{id}', [OrderController::class, 'pay']);


                     });


                 });


            OrderUserApiRoute::api();

            AddressUserRoute::api();


            WalletUserRoute::api();

        });


    }
}
