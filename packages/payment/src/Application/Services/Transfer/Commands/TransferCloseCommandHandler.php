<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Services\Transfer\TransferApplicationService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Services\ChannelAppPermissionService;
use RedJasmine\Payment\Domain\Services\Routing\TransferRoutingService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TransferCloseCommandHandler extends CommandHandler
{
    public function __construct(
        protected TransferApplicationService      $service,
        protected ChannelAppPermissionService $channelAppPermissionService,
        protected TransferRoutingService      $transferRoutingService,
    )
    {
    }

    /**
     * @param TransferCloseCommand $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     * @throws PaymentException
     */
    public function handle(TransferCloseCommand $command) : bool
    {
        Log::withContext([ 'transferNo' => $command->transferNo ]);
        Log::info($this->service::getHookName('close') . ':start');

        $this->beginDatabaseTransaction();
        try {
            $transfer = $this->service->repository->findByNo($command->transferNo);

            $transfer->close();

            $this->service->repository->update($transfer);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        Log::info($this->service::getHookName('close') . ':end');
        return true;
    }

}
