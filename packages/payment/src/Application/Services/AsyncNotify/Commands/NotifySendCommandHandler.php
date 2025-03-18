<?php

namespace RedJasmine\Payment\Application\Services\AsyncNotify\Commands;

use Exception;
use RedJasmine\Payment\Application\Services\AsyncNotify\AsyncNotifyCommandService;
use RedJasmine\Payment\Domain\Models\Enums\NotifyStatusEnum;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;

class NotifySendCommandHandler extends CommandHandler
{
    public function __construct(protected AsyncNotifyCommandService $service)
    {

    }

    /**
     * @param NotifySendCommand $command
     * @return NotifyStatusEnum
     * @throws Exception
     */
    public function handle(NotifySendCommand $command) : NotifyStatusEnum
    {
        $notify = $this->service->repository->findByNo($command->notifyNo);


        $this->service->asyncNotifyService->notify($notify);

        $this->service->repository->update($notify);

        // TODO 如何触发失败重试


        return $notify->status;

    }


}
