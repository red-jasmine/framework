<?php

namespace RedJasmine\Shopping\Application\Listeners;

use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\OrderCommandService;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;

class PaymentTradeListener
{

    public function handle($event) : void
    {
        // 如果订单是 支付成功的
        if ($event instanceof TradePaidEvent) {
            // 判断是否为 商城 生成的订单


            if ($event->trade) {

                // 订单支付成功

                $command = new OrderPaidCommand();

                $command->id = $event->trade->trade_no;

                app(OrderCommandService::class)->paid($command);


            }

        }

    }
}