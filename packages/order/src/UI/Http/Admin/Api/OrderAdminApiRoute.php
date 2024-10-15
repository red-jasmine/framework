<?php

namespace RedJasmine\Order\UI\Http\Admin\Api;

use Illuminate\Support\Facades\Route;
use RedJasmine\Order\UI\Http\Buyer\Api\Controller\OrderController;
use RedJasmine\Order\UI\Http\Buyer\Api\Controller\RefundController;


class OrderAdminApiRoute
{

    public static function route() : void
    {
        Route::group([
                         'prefix' => 'order',
                     ], function () {
            // 订单
            Route::apiResource('orders', OrderController::class)->names('order.admin.orders');
            Route::post('orders/cancel', [ OrderController::class, 'cancel' ])->name('order.admin.orders.cancel');
            Route::post('orders/paying', [ OrderController::class, 'paying' ])->name('order.admin.orders.paying');
            Route::post('orders/paid', [ OrderController::class, 'paid' ])->name('order.admin.orders.paid');
            Route::post('orders/remarks', [ OrderController::class, 'remarks' ])->name('order.admin.orders.remarks');
            // 发货
            Route::post('orders/shipping-virtual', [ OrderController::class, 'shippingVirtual' ])->name('order.admin.orders.shipping-virtual');
            Route::post('orders/shipping-card-key', [ OrderController::class, 'shippingCardKey' ])->name('order.admin.orders.shipping-card-key');
            Route::post('orders/shipping-logistics', [ OrderController::class, 'shippingLogistics' ])->name('order.admin.orders.shipping-logistics');
            // 退款售后
            Route::apiResource('refunds', RefundController::class)->names('order.admin.refunds');
            Route::post('refunds/agree-refund', [ RefundController::class, 'refundGoods' ])->name('order.admin.refunds.return-goods');
            Route::post('refunds/cancel', [ RefundController::class, 'cancel' ])->name('order.admin.refunds.cancel');
            Route::post('refunds/return-goods', [ RefundController::class, 'refundGoods' ])->name('order.admin.refunds.return-goods');

        });
    }
}
