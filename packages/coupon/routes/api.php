<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Coupon\UI\Http\Admin\Api\Controllers\CouponController as AdminCouponController;
use RedJasmine\Coupon\UI\Http\Shop\CouponShopRoute;
use RedJasmine\Coupon\UI\Http\User\Api\Controllers\UserCouponController;

// 管理员路由
Route::group([
    'prefix'     => 'api/user',
    'middleware' => ['api', 'auth:user']
], function () {

    CouponShopRoute::api();
});
