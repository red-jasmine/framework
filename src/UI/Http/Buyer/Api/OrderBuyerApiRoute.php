<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api;

use Illuminate\Support\Facades\Route;
use RedJasmine\Order\UI\Http\Buyer\Api\Controller\OrderController;
use RedJasmine\Order\UI\Http\Buyer\Api\Controller\RefundController;


class OrderBuyerApiRoute
{

    public static function route() : void
    {
        Route::group([
                         'prefix' => 'order'
                     ], function () {

            // 订单
            Route::apiResource('orders', OrderController::class)->names('order.buyer.orders');
            Route::post('orders/cancel', [ OrderController::class, 'cancel' ])->name('order.buyer.orders.cancel');
            Route::post('orders/paying', [ OrderController::class, 'paying' ])->name('order.buyer.orders.paying');
            Route::post('orders/remarks', [ OrderController::class, 'remarks' ])->name('order.buyer.orders.remarks');


            // 退款售后
            Route::apiResource('refunds', RefundController::class)->names('order.buyer.refunds');
            Route::post('refunds/cancel', [ RefundController::class, 'cancel' ])->name('order.buyer.refunds.cancel');
            Route::post('refunds/return-goods', [ RefundController::class, 'refundGoods' ])->name('order.buyer.refunds.return-goods');

        });
    }
}
