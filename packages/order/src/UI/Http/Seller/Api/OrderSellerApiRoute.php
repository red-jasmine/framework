<?php

namespace RedJasmine\Order\UI\Http\Seller\Api;

use Illuminate\Support\Facades\Route;
use RedJasmine\Order\UI\Http\Seller\Api\Controller\OrderController;
use RedJasmine\Order\UI\Http\Seller\Api\Controller\RefundController;


class OrderSellerApiRoute
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
            Route::post('orders/shipping-virtual', [ OrderController::class, 'shippingVirtual' ])->name('order.seller.orders.shipping-virtual');
            Route::post('orders/shipping-card-key', [ OrderController::class, 'shippingCardKey' ])->name('order.seller.orders.shipping-card-key');
            Route::post('orders/shipping-logistics', [ OrderController::class, 'shippingLogistics' ])->name('order.seller.orders.shipping-logistics');
            // 退款售后
            Route::apiResource('refunds', RefundController::class)->names('order.seller.refunds');
            Route::post('refunds/cancel', [ RefundController::class, 'cancel' ])->name('order.seller.refunds.cancel');
            Route::post('refunds/return-goods', [ RefundController::class, 'refundGoods' ])->name('order.seller.refunds.return-goods');
            Route::post('refunds/agree-refund', [ RefundController::class, 'refundGoods' ])->name('order.seller.refunds.return-goods');

        });
    }
}
