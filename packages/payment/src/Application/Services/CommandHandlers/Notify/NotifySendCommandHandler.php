<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Notify;

use RedJasmine\Payment\Application\Commands\Notify\NotifySendCommand;
use RedJasmine\Payment\Application\Services\AsyncNotifyCommandService;
use RedJasmine\Support\Application\CommandHandler;

class NotifySendCommandHandler extends CommandHandler
{
    public function __construct(protected AsyncNotifyCommandService $service)
    {

    }

    public function handle(NotifySendCommand $command) : void
    {

        $notify = $this->service->repository->findByNo($command->notifyNo);

        $this->service->asyncNotifyService->notify($notify);

        $this->service->repository->update($notify);


    }


}
