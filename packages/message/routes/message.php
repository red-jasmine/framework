<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Message\UI\Http\User\MessageUserRoute;
use RedJasmine\Message\UI\Http\Admin\MessageAdminRoute;
use RedJasmine\Message\UI\Http\Shop\MessageShopRoute;

/*
|--------------------------------------------------------------------------
| Message API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register message routes for your application.
|
*/

// 用户端路由
Route::group(['prefix' => 'api/user', 'middleware' => ['api']], function () {
    MessageUserRoute::api();
});

// 管理端路由
Route::group(['prefix' => 'api/admin', 'middleware' => ['api']], function () {
    MessageAdminRoute::api();
});

// 商家端路由
Route::group(['prefix' => 'api/shop', 'middleware' => ['api']], function () {
    MessageShopRoute::api();
});

// Web路由
Route::group(['middleware' => ['web']], function () {
    MessageUserRoute::web();
    MessageAdminRoute::web();
    MessageShopRoute::web();
});
