<?php

use RedJasmine\Payment\UI\Http\Payer\PaymentPayerRoute;


\Illuminate\Support\Facades\Route::group([ 'prefix' => 'payment' ], function () {
    PaymentPayerRoute::route();
});



