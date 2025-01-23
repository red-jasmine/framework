<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\UserCommandService;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\UserRegisterServiceManager;
use Throwable;

class UserRegisterCommandHandler extends CommandHandler
{
    public function __construct(
        public UserCommandService $service,
        public UserRegisterServiceManager $userRegisterService
    ) {
    }

    public function handle(UserRegisterCommand $command) : User
    {
        $this->beginDatabaseTransaction();

        try {
            $user = $this->userRegisterService->register($command);

            $this->service->repository->store($user);
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
