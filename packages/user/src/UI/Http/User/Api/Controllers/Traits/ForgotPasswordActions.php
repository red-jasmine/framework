<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Application\Services\Commands\ForgotPasswordCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\ForgotPasswordCommand;

/**
 * @property BaseUserApplicationService $service
 */
trait ForgotPasswordActions
{


    public function captcha(Request $request) : JsonResponse
    {
        $command = ForgotPasswordCaptchaCommand::from($request);

        $this->service->forgotPasswordCaptcha($command);

        return static::success();
    }

    public function resetPassword(Request $request) : JsonResponse
    {
        $command = ForgotPasswordCommand::from($request);

        $this->service->forgotPassword($command);

        return static::success();
    }

}