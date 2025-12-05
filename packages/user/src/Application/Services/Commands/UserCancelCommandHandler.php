<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use Throwable;

class UserCancelCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service,
    ) {
    }


    /**
     * @param  UserCancelCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(UserCancelCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $user = $this->service->repository->find($command->id);

            $user->cancel();

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