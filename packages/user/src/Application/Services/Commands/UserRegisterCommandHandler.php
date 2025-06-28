<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\UserLoginService;
use RedJasmine\User\Domain\Services\Register\UserRegisterService;
use Throwable;

class UserRegisterCommandHandler extends CommandHandler
{
    public UserLoginService    $loginService;
    public UserRegisterService $userRegisterService;

    public function __construct(
        public BaseUserApplicationService $service,
    ) {
        $this->userRegisterService = new UserRegisterService(
            $this->service->readRepository,
            $this->service->getGuard(),
            $this->service->newModel()
        );
        $this->loginService        = new UserLoginService(
            $this->service->readRepository,
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
