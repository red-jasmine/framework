<?php

namespace RedJasmine\Coupon\UI\Http\Shop;

use Illuminate\Support\Facades\Route;
use RedJasmine\Coupon\UI\Http\Shop\Api\Controllers\CouponController;
use RedJasmine\Coupon\UI\Http\Shop\Api\Controllers\UserCouponController;
use RedJasmine\Coupon\UI\Http\Shop\Api\Controllers\CouponUsageController;

class CouponShopRoute
{


    public static function api() : void
    {
        Route::prefix('coupon')
             ->group(function () {

                 // 优惠券相关路由
                 Route::apiResource('coupons', CouponController::class)->names('shop.api.coupons');
                 Route::post('coupons/{id}/issue', [CouponController::class, 'issue'])->name('shop.api.coupons.issue');
                 Route::post('coupons/{id}/publish', [CouponController::class, 'publish'])->name('shop.api.coupons.publish');
                 Route::post('coupons/{id}/pause', [CouponController::class, 'pause'])->name('shop.api.coupons.pause');

                 // 用户优惠券相关路由（商家端查看）
                 Route::prefix('user-coupons')->group(function () {
                     Route::get('/', [UserCouponController::class, 'index'])->name('shop.api.user-coupons.index');
                     Route::get('/{id}', [UserCouponController::class, 'show'])->name('shop.api.user-coupons.show');

                 });

                 // 优惠券使用记录相关路由
                 Route::prefix('coupon-usages')->group(function () {
                     Route::get('/', [CouponUsageController::class, 'index'])->name('shop.api.coupon-usage.index');
                     Route::get('/{id}', [CouponUsageController::class, 'show'])->name('shop.api.coupon-usage.show');
                 });

             });
    }

}