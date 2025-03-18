<?php

namespace RedJasmine\Payment\Application\Services\Refund\Commands;

use RedJasmine\Payment\Application\Services\Refund\RefundCommandService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundCancelCommandHandler extends CommandHandler
{
    public function __construct(protected RefundCommandService $service)
    {
    }


    /**
     * @param  RefundCancelCommand  $command
     *
     * @return bool
     * @throws AbstractException
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

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return true;
    }
}
