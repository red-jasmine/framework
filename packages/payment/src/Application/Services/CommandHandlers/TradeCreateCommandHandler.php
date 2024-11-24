<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use RedJasmine\Payment\Application\Commands\Trade\TradeCreateCommand;
use RedJasmine\Payment\Domain\Models\PaymentTrade;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TradeCreateCommandHandler extends CommandHandler
{

    public function __construct()
    {
    }

    /**
     * @param TradeCreateCommand $command
     * @return PaymentTrade
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(TradeCreateCommand $command) : PaymentTrade
    {

        $this->beginDatabaseTransaction();

        try {

            $model = PaymentTrade::newModel();














            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $model;

    }

}
