<?php

namespace RedJasmine\UserCore\Application\Services\Commands\Register;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\UserCore\Domain\Services\Login\UserLoginService;
use RedJasmine\UserCore\Domain\Services\Register\UserRegisterService;
use Throwable;

class UserRegisterCommandHandler extends CommandHandler
{
    public UserLoginService    $loginService;
    public UserRegisterService $userRegisterService;

    public function __construct(
        public BaseUserApplicationService $service,
    ) {
        $this->userRegisterService = new UserRegisterService(
            $this->service->repository,
            $this->service->getGuard(),
            $this->service->newModel()
        );
        $this->loginService        = new UserLoginService(
            $this->service->repository,
            $this->service->getGuard(),
        );
    }

    public function handle(UserRegisterCommand $command) : UserTokenData
    {


        $this->beginDatabaseTransaction();

        try {
            $user = $this->userRegisterService->register($command);

            $this->service->repository->store($user);

            $userTokenData = $this->loginService->token($user);

            $this->commitDatabaseTransaction();

        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return $userTokenData;


    }

}
