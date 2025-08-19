<?php

namespace RedJasmine\Message\Application\Services\Message\Commands;

use RedJasmine\Message\Application\Services\Message\MessageApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class MessageAllMarkAsReadCommandHandler extends CommandHandler
{
    public function __construct(
        protected MessageApplicationService $service
    ) {

    }

    /**
     * @param  MessageMarkAsReadCommand  $command
     *
     * @return void
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(MessageMarkAsReadCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $this->service->repository->allMarkAsReadAll($command->biz, $command->owner);
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

    }

}