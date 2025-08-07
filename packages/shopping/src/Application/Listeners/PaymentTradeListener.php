<?php

namespace RedJasmine\Shopping\Application\Listeners;

use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Shopping\Domain\Contracts\PaymentServiceInterface;

class PaymentTradeListener
{

    public function handle($event) : void
    {
        // 如果订单是 支付成功的
        if ($event instanceof TradePaidEvent) {
            // TODO 判断是否 为商城订单

            if ($event->trade && $event->trade->biz === PaymentServiceInterface::BIZ) {

                // 订单支付成功

                $command = new OrderPaidCommand();

                $command->orderNo        = $event->trade->merchant_trade_order_no;
                $command->orderPaymentId = null;

                $command->amount;
                $command->paymentChannel;
                $command->paymentChannelNo;
                $command->paymentMethod;
                $command->paymentTime;


                app(OrderApplicationService::class)->paid($command);


            }

        }

    }
}