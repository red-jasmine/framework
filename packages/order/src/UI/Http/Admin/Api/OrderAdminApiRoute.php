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
            Route::post('orders/dummy-shipping', [ OrderController::class, 'dummyShipping' ])->name('order.admin.orders.dummy-shipping');
            Route::post('orders/card-key-shipping', [ OrderController::class, 'cardKeyShipping' ])->name('order.admin.orders.card-key-shipping');
            Route::post('orders/logistics-shipping', [ OrderController::class, 'logisticsShipping' ])->name('order.admin.orders.logistics-shipping');
            // 退款售后
            Route::apiResource('refunds', RefundController::class)->names('order.admin.refunds');
            Route::post('refunds/agree-refund', [ RefundController::class, 'refundGoods' ])->name('order.admin.refunds.return-goods');
            Route::post('refunds/cancel', [ RefundController::class, 'cancel' ])->name('order.admin.refunds.cancel');
            Route::post('refunds/return-goods', [ RefundController::class, 'refundGoods' ])->name('order.admin.refunds.return-goods');

        });
    }
}
