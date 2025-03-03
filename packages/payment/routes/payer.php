<?php

use RedJasmine\Payment\UI\Http\Payer\PaymentPayerRoute;


\Illuminate\Support\Facades\Route::group([ 'prefix' => 'payment' ], function () {
    PaymentPayerRoute::route();
});

\Illuminate\Support\Facades\Route::group([ 'prefix' => 'api/payment' ], function () {
    PaymentPayerRoute::api();
});



