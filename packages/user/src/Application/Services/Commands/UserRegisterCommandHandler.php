<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\UserLoginService;
use RedJasmine\User\Domain\Services\Register\UserRegisterService;
use Throwable;

class UserRegisterCommandHandler extends CommandHandler
{
    public function __construct(
        public UserApplicationService $service,
        public UserRegisterService $userRegisterService,
        public UserLoginService $loginService,
    ) {
    }

    public function handle(UserRegisterCommand $command) : UserTokenData
    {


        $this->beginDatabaseTransaction();

        try {
            $user = $this->userRegisterService->register($command);

            $this->service->repository->store($user);

            $userTokenData = $this->loginService->token($user);

            $this->commitDatabaseTransaction();

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return $userTokenData;


    }

}
