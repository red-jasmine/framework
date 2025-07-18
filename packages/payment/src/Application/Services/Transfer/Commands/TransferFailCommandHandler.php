<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Services\Transfer\TransferApplicationService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TransferFailCommandHandler extends CommandHandler
{
    public function __construct(
        protected TransferApplicationService $service
    )
    {
    }

    /**
     * @param TransferFailCommand $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     * @throws PaymentException
     */
    public function handle(TransferFailCommand $command) : bool
    {
        Log::withContext([ 'transferNo' => $command->transferNo ]);
        Log::info($this->service::getHookName('fail') . ':start');

        $this->beginDatabaseTransaction();
        try {
            $transfer = $this->service->repository->findByNo($command->transferNo);

            $transfer->fail($command);

            $this->service->repository->update($transfer);

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
