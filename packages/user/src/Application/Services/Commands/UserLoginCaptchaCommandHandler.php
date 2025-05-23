<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Services\Login\UserLoginService;

class UserLoginCaptchaCommandHandler extends CommandHandler
{
    public function __construct(
        public BaseUserApplicationService $service,
        public UserLoginService $loginService
    ) {
    }

    public function handle(UserLoginCaptchaCommand $command) : bool
    {
        return $this->loginService->captcha($command);
    }

}
