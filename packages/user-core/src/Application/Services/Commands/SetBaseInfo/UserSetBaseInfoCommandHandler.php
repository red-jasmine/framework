<?php

namespace RedJasmine\UserCore\Application\Services\Commands\SetBaseInfo;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use Throwable;

class UserSetBaseInfoCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service
    ) {
    }


    /**
     * @param  UserSetBaseInfoCommand  $command
     *
     * @return User
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserSetBaseInfoCommand $command) : User
    {
        $this->beginDatabaseTransaction();

        try {

            $user = $this->service->repository->find($command->id);

            $user->setUserBaseInfo($command);

            $this->service->repository->update($user);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $user;

    }
}