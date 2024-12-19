<?php

namespace RedJasmine\Payment\UI\Http\Payer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Payment\UI\Http\Payer\Web\TradeController;

class PaymentPayerRoute
{


    public static function route() : void
    {
        Route::group([
                         'prefix' => 'payer',
                     ], function () {
            Route::get('trades/{id}/{time}/{signature}', [ TradeController::class, 'show' ])->name('payment.payer.trades.show');
        });

    }
}
