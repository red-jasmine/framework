<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Services\Transfer\TransferCommandService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Services\ChannelAppPermissionService;
use RedJasmine\Payment\Domain\Services\Routing\TransferRoutingService;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TransferCancelCommandHandler extends CommandHandler
{
    public function __construct(
        protected TransferCommandService      $service,
        protected ChannelAppPermissionService $channelAppPermissionService,
        protected TransferRoutingService      $transferRoutingService,
    )
    {
    }

    /**
     * @param TransferCancelCommand $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     * @throws PaymentException
     */
    public function handle(TransferCancelCommand $command) : bool
    {
        Log::withContext([ 'transferNo' => $command->transferNo ]);
        Log::info($this->service::getHookName('cancel') . ':start');

        $this->beginDatabaseTransaction();
        try {
            $transfer = $this->service->repository->findByNo($command->transferNo);

            $transfer->executing();

            $this->service->repository->update($transfer);
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        Log::info($this->service::getHookName('cancel') . ':end');
        return true;
    }

}
