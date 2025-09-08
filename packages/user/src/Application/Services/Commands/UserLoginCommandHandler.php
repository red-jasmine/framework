<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\UserLoginService;

class UserLoginCommandHandler extends CommandHandler
{
    public UserLoginService $loginService;
    
    public function __construct(
        public BaseUserApplicationService $service,

    ) {

        $this->loginService = new UserLoginService(
            $this->service->repository,
            $this->service->getGuard(),
        );
    }

    public function handle(UserLoginCommand $command) : UserTokenData
    {

        return $this->loginService->login($command);
    }

}
