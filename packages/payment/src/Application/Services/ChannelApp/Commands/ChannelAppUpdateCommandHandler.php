<?php

namespace RedJasmine\Payment\Application\Services\ChannelApp\Commands;

use RedJasmine\Payment\Application\Services\ChannelApp\ChannelAppCommandService;
use RedJasmine\Payment\Domain\Models\ChannelApp;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class ChannelAppUpdateCommandHandler extends CommandHandler
{

    public function __construct(
        protected ChannelAppCommandService $service
    )
    {
    }

    public function handle(ChannelAppUpdateCommand $command) : ChannelApp
    {
        $this->beginDatabaseTransaction();

        try {
            // 渠道商户
            $channelApp = $this->service->repository->find($command->id);
            $this->service->transformer->transform($command, $channelApp);
            $this->service->repository->update($channelApp);
            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $channelApp;

    }

}
