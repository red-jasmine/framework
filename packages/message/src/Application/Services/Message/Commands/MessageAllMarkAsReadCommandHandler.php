<?php

namespace RedJasmine\Message\Application\Services\Message\Commands;

use RedJasmine\Message\Application\Services\Message\MessageApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
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
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(MessageMarkAsReadCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $this->service->repository->allMarkAsReadAll($command->biz, $command->owner);
            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

    }

}