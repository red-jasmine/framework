<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Services\ForgotPassword\ForgotPasswordService;

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
