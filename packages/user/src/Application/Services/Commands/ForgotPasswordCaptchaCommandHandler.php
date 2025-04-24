<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Services\ForgotPassword\ForgotPasswordService;
use RedJasmine\User\Domain\Services\Login\UserLoginService;

class ForgotPasswordCaptchaCommandHandler extends CommandHandler
{
    public function __construct(
        public UserApplicationService $service,
        public ForgotPasswordService $forgotPassword,
    ) {
    }

    public function handle(ForgotPasswordCaptchaCommand $command) : bool
    {
        return $this->forgotPassword->captcha($command);
    }

}
