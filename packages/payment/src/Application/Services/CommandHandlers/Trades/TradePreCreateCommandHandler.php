<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Trades;

use RedJasmine\Payment\Application\Commands\Trade\TradeData;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TradePreCreateCommandHandler extends CommandHandler
{


    public function handle(TradeData $command) : Trade
    {
        $this->beginDatabaseTransaction();

        try {
            $trade = Trade::newModel();
            // TODO


            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $trade;
    }
}
