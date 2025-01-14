<?php

namespace RedJasmine\Payment\Application\Services\Refund\Commands;

use RedJasmine\Payment\Application\Services\Refund\RefundCommandService;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundExecutingCommandHandler extends CommandHandler
{
    public function __construct(protected RefundCommandService $service)
    {
    }


    /**
     * @param  RefundExecutingCommand  $command
     *
     * @return Refund
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(RefundExecutingCommand $command) : Refund
    {

        $this->beginDatabaseTransaction();

        try {
            $refund = $this->service->refundRepository->findByNo($command->refundNo);

            $refund->executing();

            $this->service->refundRepository->update($refund);

            $this->commitDatabaseTransaction();

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $refund;
    }
}
