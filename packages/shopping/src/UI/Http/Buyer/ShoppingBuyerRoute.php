<?php

namespace RedJasmine\Shopping\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers\OrderController;
use RedJasmine\ShoppingCart\UI\Http\ShoppingCartRoute;

class ShoppingBuyerRoute
{


    public static function api()
    {

        Route::group([
            'prefix' => 'shopping'
        ], function () {


            Route::post('buy', [OrderController::class, 'buy']);


        });

        ShoppingCartRoute::api();


    }
}
