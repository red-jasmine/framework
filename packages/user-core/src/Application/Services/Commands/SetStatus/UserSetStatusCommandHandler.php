<?php

namespace RedJasmine\UserCore\Application\Services\Commands\SetStatus;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Application\Services\Commands\SetPassword\UserSetStatusCommand;
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
     * @throws BaseException
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
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return true;

    }
}