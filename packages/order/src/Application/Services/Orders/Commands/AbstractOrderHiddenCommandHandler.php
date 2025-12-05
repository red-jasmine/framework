<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Support\Exceptions\BaseException;
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
     *
     * @return void
     * @throws BaseException
     * @throws Throwable
     * @throws OrderException
     */
    public function handle(OrderHiddenCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            $order->hiddenOrder($this->getTradeParty(), $command->isHidden);

            $this->service->repository->update($order);

            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
    }

}
