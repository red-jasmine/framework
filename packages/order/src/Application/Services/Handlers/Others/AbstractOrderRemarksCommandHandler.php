<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

abstract class AbstractOrderRemarksCommandHandler extends AbstractOrderCommandHandler
{


    protected TradePartyEnums $tradeParty;

    public function getTradeParty() : TradePartyEnums
    {
        return $this->tradeParty;
    }

    public function setTradeParty(TradePartyEnums $tradeParty) : static
    {
        $this->tradeParty = $tradeParty;
        return $this;
    }


    /**
     * @param OrderRemarksCommand $command
     * @return void
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(OrderRemarksCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);

            $order->remarks($this->tradeParty, $command->remarks, $command->orderProductId, $command->isAppend);

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
