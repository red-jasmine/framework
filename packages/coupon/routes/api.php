<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Coupon\UI\Http\Admin\Api\Controllers\CouponController as AdminCouponController;
use RedJasmine\Coupon\UI\Http\Shop\CouponShopRoute;
use RedJasmine\Coupon\UI\Http\User\Api\Controllers\UserCouponController;
use RedJasmine\Coupon\UI\Http\User\CouponUserRoute;

// 用户路由
Route::group([
    'prefix'     => 'api/user',
    'middleware' => ['api', 'auth:user']
], function () {
    CouponUserRoute::api();
});

// 商家路由
Route::group([
    'prefix'     => 'api/shop',
    'middleware' => ['api', 'auth:shop']
], function () {
    CouponShopRoute::api();
});

// 管理员路由
Route::group([
    'prefix'     => 'api/admin',
    'middleware' => ['api', 'auth:admin']
], function () {
    // 管理员路由定义
    Route::apiResource('coupons', AdminCouponController::class);
});
