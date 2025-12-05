<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class TradePaidCommandHandler extends AbstractTradeCommandHandler
{
    /**
     * @param  TradePaidCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     * @throws PaymentException
     */
    public function handle(TradePaidCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            $trade = $this->service->repository->findByNo($command->tradeNo);

            $trade->paid($command);

            $this->service->repository->update($trade);

            $this->commitDatabaseTransaction();

        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return true;

    }

}
