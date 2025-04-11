<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\UserLoginService;

class UserLoginCommandHandler extends CommandHandler
{
    public function __construct(
        public UserApplicationService $service,
        public UserLoginService $loginService
    ) {
    }

    public function handle(UserLoginCommand $command) : UserTokenData
    {
        return $this->loginService->login($command);
    }

}
