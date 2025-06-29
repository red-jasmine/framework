<?php


use RedJasmine\User\UI\Http\User\UserRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api/user',
    'middleware' => ['api'],

], function () {

    UserRoute::api();

});