<?php

namespace RedJasmine\UserCore\Application\Services\Commands\Login;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\UserCore\Domain\Services\Login\UserLoginService;

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
