<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Message\UI\Http\User\MessageUserRoute;


/*
|--------------------------------------------------------------------------
| Message API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register message routes for your application.
|
*/

// 用户端路由
Route::group(['prefix' => 'api/user', 'middleware' => ['api','auth:user']], function () {
    MessageUserRoute::api();
});

