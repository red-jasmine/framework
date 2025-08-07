<?php

namespace RedJasmine\Shopping\Infrastructure\Services\Listeners;

use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Shopping\Application\Services\Orders\Commands\PaidCommand;
use RedJasmine\Shopping\Application\Services\Orders\ShoppingOrderCommandService;
use RedJasmine\Shopping\Domain\Contracts\PaymentServiceInterface;

class PaymentTradeListener
{

    public function handle($event) : void
    {

        // if ($event instanceof TradePaidEvent) {
        //     if ($event->trade && $event->trade->biz === PaymentServiceInterface::BIZ) {
        //
        //         // 订单支付成功
        //
        //         $command                 = new PaidCommand();
        //         $trade                   = $event->trade;
        //         $command->orderNo        = $trade->merchant_trade_order_no;
        //         $command->orderPaymentId = (int) $trade->merchant_trade_no;
        //         $command->amount         = $trade->amount;
        //
        //         $command->paymentType = 'payment';
        //         $command->paymentId   = $trade->trade_no;
        //
        //         $command->paymentChannel   = $trade->channel_code;
        //         $command->paymentChannelNo = $trade->channel_trade_no;
        //         $command->paymentMethod    = $trade->channel_product_code;
        //         $command->paymentTime      = $trade->paid_time->format('Y-m-d H:i:s');
        //
        //
        //         app(ShoppingOrderCommandService::class)->paid($command);
        //
        //
        //     }
        // }


    }
}