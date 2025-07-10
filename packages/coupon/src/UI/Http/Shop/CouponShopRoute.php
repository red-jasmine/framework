<?php

namespace RedJasmine\Coupon\UI\Http\Shop;

use Illuminate\Support\Facades\Route;
use RedJasmine\Coupon\UI\Http\Shop\Api\Controllers\CouponController;

class CouponShopRoute
{


    public static function api() : void
    {
        Route::prefix('prefix')
             ->group(function () {

                 Route::apiResource('coupons', CouponController::class)->names('shop.api.coupons');

             });
    }

}