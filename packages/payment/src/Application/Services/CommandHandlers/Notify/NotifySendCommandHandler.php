<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Notify;

use Exception;
use RedJasmine\Payment\Application\Commands\Notify\NotifySendCommand;
use RedJasmine\Payment\Application\Services\AsyncNotifyCommandService;
use RedJasmine\Support\Application\CommandHandler;

class NotifySendCommandHandler extends CommandHandler
{
    public function __construct(protected AsyncNotifyCommandService $service)
    {

    }

    /**
     * @param NotifySendCommand $command
     * @return void
     * @throws Exception
     */
    public function handle(NotifySendCommand $command) : void
    {
        $notify = $this->service->repository->findByNo($command->notifyNo);


        $this->service->asyncNotifyService->notify($notify);

        $this->service->repository->update($notify);


    }


}
