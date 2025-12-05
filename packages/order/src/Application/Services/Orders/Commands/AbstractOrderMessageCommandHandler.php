<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

abstract class AbstractOrderMessageCommandHandler extends AbstractOrderCommandHandler
{


    protected TradePartyEnums $tradeParty;

    public function getTradeParty() : TradePartyEnums
    {
        return $this->tradeParty;
    }

    public function setTradeParty(TradePartyEnums $tradeParty) : AbstractOrderMessageCommandHandler
    {
        $this->tradeParty = $tradeParty;
        return $this;
    }


    /**
     * @param OrderMessageCommand $command
     *
     * @return void
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(OrderMessageCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            $order->message($this->tradeParty, $command->message,
                $command->orderProductNo, $command->isAppend);

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
