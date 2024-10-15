<?php

namespace RedJasmine\Payment\Http\Controllers\Common;


use Illuminate\Support\Facades\Route;
use RedJasmine\Payment\Http\Controllers\Common\Web\PaymentController;

class PaymentRoutes
{
    public static function api() : void
    {

    }

    public static function web() : void
    {

        Route::group([
                         'prefix'     => 'payments',
                         'middleware' => [ 'web' ]
                     ], function () {
            Route::get('{id}/paying', [ PaymentController::class, 'paying' ]);

        });

    }
}
