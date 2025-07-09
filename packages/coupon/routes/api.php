<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Coupon\UI\Http\Admin\Api\Controllers\CouponController as AdminCouponController;
use RedJasmine\Coupon\UI\Http\User\Api\Controllers\UserCouponController;

// 管理员路由
Route::prefix('admin')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::apiResource('coupons', AdminCouponController::class);
        Route::post('coupons/{coupon}/publish', [AdminCouponController::class, 'publish']);
        Route::post('coupons/{coupon}/pause', [AdminCouponController::class, 'pause']);
        Route::post('coupons/{coupon}/issue', [AdminCouponController::class, 'issue']);
    });

// 用户路由
Route::prefix('user')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::apiResource('user-coupons', UserCouponController::class)->only(['index', 'show']);
        Route::post('user-coupons/{userCoupon}/consume', [UserCouponController::class, 'consume']);
    });