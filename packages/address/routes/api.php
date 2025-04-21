<?php


use RedJasmine\Address\UI\Http\User\UserRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api']
], function () {


    UserRoute::api();
});