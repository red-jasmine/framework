<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Support\Exceptions\BaseException;
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
     * @param  OrderRemarksCommand  $command
     *
     * @return void
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(OrderRemarksCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            $order->remarks(
                $this->tradeParty,
                $command->remarks,
                $command->orderProductNo,
                $command->isAppend
            );

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
