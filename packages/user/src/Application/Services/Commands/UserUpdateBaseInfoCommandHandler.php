<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Models\User;
use Throwable;

class UserUpdateBaseInfoCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service
    ) {
    }


    public function handle(UserUpdateBaseInfoCommand $command) : User
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