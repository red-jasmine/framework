<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

abstract class AbstractOrderHiddenCommandHandler extends AbstractOrderCommandHandler
{


    protected TradePartyEnums $tradeParty;

    public function getTradeParty() : TradePartyEnums
    {
        return $this->tradeParty;
    }

    public function setTradeParty(TradePartyEnums $tradeParty) : AbstractOrderHiddenCommandHandler
    {
        $this->tradeParty = $tradeParty;
        return $this;
    }


    /**
     * @param OrderHiddenCommand $command
     * @return void
     * @throws AbstractException
     * @throws Throwable
     * @throws OrderException
     */
    public function handle(OrderHiddenCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);

            $order->hiddenOrder($this->getTradeParty(), $command->isHidden);

            $this->service->repository->update($order);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
    }

}
