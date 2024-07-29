<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderRemarksCommandHandler extends AbstractOrderCommandHandler
{


    protected TradePartyEnums $tradeParty;

    public function getTradeParty() : TradePartyEnums
    {
        return $this->tradeParty;
    }

    public function setTradeParty(TradePartyEnums $tradeParty) : OrderRemarksCommandHandler
    {
        $this->tradeParty = $tradeParty;
        return $this;
    }


    public function handle(OrderRemarksCommand $command) : void
    {

        $order = $this->find($command->id);

        $this->execute(
            execute: fn() => $order->remarks($this->tradeParty, $command->remarks, $command->orderProductId),
            persistence: fn() => $this->orderRepository->update($order)
        );
    }

}
