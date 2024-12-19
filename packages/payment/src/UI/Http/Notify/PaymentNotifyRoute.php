<?php

namespace RedJasmine\Payment\UI\Http\Notify;

use Illuminate\Support\Facades\Route;
use RedJasmine\Payment\UI\Http\Notify\Api\NotifyController;

class PaymentNotifyRoute
{
    public static function route() : void
    {
        Route::group([
                         'prefix' => 'notify',
                     ], function () {


            Route::any('notify/{channel}/{app}/{time}/{signature}', [ NotifyController::class, 'notify' ])->name('payment.notify.notify');
        });

    }
}
