<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderHiddenCommandHandler extends AbstractOrderCommandHandler
{


    protected TradePartyEnums $tradeParty;

    public function getTradeParty() : TradePartyEnums
    {
        return $this->tradeParty;
    }

    public function setTradeParty(TradePartyEnums $tradeParty) : OrderHiddenCommandHandler
    {
        $this->tradeParty = $tradeParty;
        return $this;
    }


    public function handle(OrderHiddenCommand $command) : void
    {
        $order = $this->find($command->id);
        $this->execute(
            execute: fn() => $order->hiddenOrder($this->getTradeParty(), $command->isHidden),
            persistence: fn() => $this->orderRepository->update($order)
        );
    }

}
