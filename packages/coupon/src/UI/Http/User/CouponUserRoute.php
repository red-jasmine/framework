<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Coupon\UI\Http\User\Api\Controllers\CouponController;
use RedJasmine\Coupon\UI\Http\User\Api\Controllers\UserCouponController;

class CouponUserRoute
{
    public static function api() : void
    {
        Route::prefix('coupon')
             ->name('coupon.api.user.')
             ->middleware('auth:user')
             ->group(function () {
                 // 优惠券相关路由（查看、领取、使用）
                 Route::get('coupons', [CouponController::class, 'index']);
                 Route::get('coupons/{id}', [CouponController::class, 'show']);
                 Route::post('coupons/{id}/receive', [CouponController::class, 'receive']);


                 // 用户优惠券相关路由（只读查询）
                 Route::apiResource('user-coupons', UserCouponController::class)->only(['index', 'show']);

             });
    }

    public static function web() : void
    {
        Route::group(['prefix' => 'coupon'], function () {
            // 用户端 Web 路由（如果需要）
            // 暂时留空
        });
    }
} 