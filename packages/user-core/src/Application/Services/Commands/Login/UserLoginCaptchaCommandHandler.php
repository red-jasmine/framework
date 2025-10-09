<?php

namespace RedJasmine\UserCore\Application\Services\Commands\Login;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\Login\UserLoginService;

class UserLoginCaptchaCommandHandler extends CommandHandler
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


    public function handle(UserLoginCaptchaCommand $command) : bool
    {
        return $this->loginService->captcha($command);
    }

}
