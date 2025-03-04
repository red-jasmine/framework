<?php

namespace RedJasmine\Payment\UI\Http\Payer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Payment\UI\Http\Payer\Web\TradeController;

class PaymentPayerRoute
{
    public static function api() : void
    {
        Route::group([
            'prefix' => 'payer',
        ], function () {

            Route::post('trades/ready', [Api\Controllers\TradeController::class, 'ready'])->name('payment.payer.api.trades.ready');
            Route::post('trades/paying', [Api\Controllers\TradeController::class, 'paying'])->name('payment.payer.api.trades.paying');


        });
    }

    public static function route() : void
    {
        Route::group([
            'prefix' => 'payer',
        ], function () {

            Route::any('trades/pay', [TradeController::class, 'pay'])
                 ->name('payment.payer.trades.pay');
            Route::get('trades/{id}/{time}/{signature}',
                [TradeController::class, 'show'])->name('payment.payer.trades.show');

        });


    }
}
