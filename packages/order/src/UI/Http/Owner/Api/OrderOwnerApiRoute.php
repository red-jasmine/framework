<?php

namespace RedJasmine\Order\UI\Http\Seller\Api;

use Illuminate\Support\Facades\Route;
use RedJasmine\Order\UI\Http\Seller\Api\Controller\OrderController;
use RedJasmine\Order\UI\Http\Seller\Api\Controller\RefundController;


class OrderOwnerApiRoute
{

    public static function route() : void
    {
        Route::group([
                         'prefix' => 'order'
                     ], function () {

            // 订单
            Route::apiResource('orders', OrderController::class)->names('order.seller.orders');
            Route::post('orders/cancel', [ OrderController::class, 'cancel' ])->name('order.seller.orders.cancel');
            Route::post('orders/paying', [ OrderController::class, 'paying' ])->name('order.seller.orders.paying');
            Route::post('orders/paid', [ OrderController::class, 'paid' ])->name('order.seller.orders.paid');
            Route::post('orders/remarks', [ OrderController::class, 'remarks' ])->name('order.seller.orders.remarks');
            // 发货
            Route::post('orders/dummy-shipping', [ OrderController::class, 'dummyShipping' ])->name('order.seller.orders.dummy-shipping');
            Route::post('orders/card-key-shipping', [ OrderController::class, 'cardKeyShipping' ])->name('order.seller.orders.card-key-shipping');
            Route::post('orders/logistics-shipping', [ OrderController::class, 'logisticsShipping' ])->name('order.seller.orders.logistics-shipping');
            // 退款售后
            Route::apiResource('refunds', RefundController::class)->names('order.seller.refunds');
            Route::post('refunds/cancel', [ RefundController::class, 'cancel' ])->name('order.seller.refunds.cancel');
            Route::post('refunds/return-goods', [ RefundController::class, 'agreeReturnGoods' ])->name('order.seller.refunds.return-goods');
            Route::post('refunds/agree-refund', [ RefundController::class, 'agreeRefund' ])->name('order.seller.refunds.return-goods');

        });
    }
}
