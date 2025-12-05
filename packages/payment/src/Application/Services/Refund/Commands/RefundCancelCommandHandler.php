<?php

namespace RedJasmine\Payment\Application\Services\Refund\Commands;

use RedJasmine\Payment\Application\Services\Refund\RefundApplicationService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class RefundCancelCommandHandler extends CommandHandler
{
    public function __construct(protected RefundApplicationService $service)
    {
    }


    /**
     * @param  RefundCancelCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     * @throws PaymentException
     */
    public function handle(RefundCancelCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $refund = $this->service->refundRepository->findByNo($command->refundNo);

            $refund->cancel();

            $this->service->refundRepository->update($refund);

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
