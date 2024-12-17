<?php


use RedJasmine\Payment\UI\Http\Notify\PaymentNotifyRoute;

use Illuminate\Support\Facades\Route;


Route::group([ 'prefix' => 'payment' ], function () {

    PaymentNotifyRoute::route();


});
