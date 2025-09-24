<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Vip\UI\Http\User\Api\Controllers\UserVipController;
use RedJasmine\Vip\UI\Http\User\Api\Controllers\VipController;
use RedJasmine\Vip\UI\Http\User\Api\Controllers\VipProductController;

Route::group([
    'prefix'     => 'api/vip',
    'middleware' => ['api', 'auth:owner']
], function () {

    Route::group([
        'prefix'    => 'user',
        'namespace' => "\\RedJasmine\\Vip\\UI\\Http\\User\\Api\\Controllers"
    ], function () {

        Route::get('vips/{biz}/{type}', [VipController::class, 'show']);
        Route::get('vips', [VipController::class, 'index']);



        Route::post('vip-products/buy', [VipProductController::class,'buy']);
        Route::apiResource('vip-products', VipProductController::class);


        Route::get('/user-vips/{biz}/{type}', [UserVipController::class, 'vip']);
        Route::get('/user-vips', [UserVipController::class, 'vips']);
    });

});