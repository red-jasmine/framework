<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Coupon\UI\Http\User\Api\Controllers\CouponController;
use RedJasmine\Coupon\UI\Http\User\Api\Controllers\UserCouponController;

class CouponUserRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'coupon'], function () {
            // 优惠券相关路由（查看、领取、使用）
            Route::get('coupons', [CouponController::class, 'index']);
            Route::get('coupons/{id}', [CouponController::class, 'show']);
            Route::post('coupons/{id}/receive', [CouponController::class, 'receive']);
            Route::post('coupons/consume/{userCouponId}', [CouponController::class, 'consume']);
            
            // 用户优惠券相关路由（只读查询）
            Route::get('user-coupons', [UserCouponController::class, 'index']);
            Route::get('user-coupons/{id}', [UserCouponController::class, 'show']);
        });
    }

    public static function web(): void
    {
        Route::group(['prefix' => 'coupon'], function () {
            // 用户端 Web 路由（如果需要）
            // 暂时留空
        });
    }
} 