<?php

namespace RedJasmine\UserCore\Application\Services\Commands\ForgotPassword;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\ForgotPassword\ForgotPasswordService;

class ForgotPasswordCaptchaCommandHandler extends CommandHandler
{
    public function __construct(
        public BaseUserApplicationService $service,
        public ForgotPasswordService $forgotPassword,
    ) {
    }

    public function handle(ForgotPasswordCaptchaCommand $command) : bool
    {
        return $this->forgotPassword->captcha($command);
    }

}
