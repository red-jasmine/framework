<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class AbstractRefundRemarksCommandHandler extends AbstractRefundCommandHandler
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

    public function handle(RefundRemarksCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $refund = $this->find($command->id);

            $refund->remarks($this->getTradeParty(), $command->remarks, $command->isAppend);

            $this->service->repository->update($refund);


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
