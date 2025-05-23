<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use Throwable;

class UserSetStatusCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service,

    ) {
    }


    /**
     * @param  UserSetStatusCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserSetStatusCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $user = $this->service->repository->find($command->id);

            $user->setStatus($command->status);

            $this->service->repository->update($user);

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