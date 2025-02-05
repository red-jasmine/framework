<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\User\Application\Services\UserCommandService;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\UserLoginService;

class UserLoginCommandHandler extends CommandHandler
{
    public function __construct(
        public UserCommandService $service,
        public UserLoginService $loginService
    ) {
    }

    public function handle(UserLoginCommand $command) : UserTokenData
    {
        return $this->loginService->login($command);
    }

}
