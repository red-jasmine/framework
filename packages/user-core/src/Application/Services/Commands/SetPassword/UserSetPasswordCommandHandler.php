<?php

namespace RedJasmine\UserCore\Application\Services\Commands\SetPassword;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Application\Services\Commands\SetStatus\UserSetPasswordCommand;
use Throwable;

class UserSetPasswordCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service,
    ) {
    }

    /**
     * @param  UserSetPasswordCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(UserSetPasswordCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $user = $this->service->repository->find($command->id);

            $user->setPassword($command->password);

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