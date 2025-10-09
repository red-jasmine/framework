<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginCaptchaCommand;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginCommand;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginOrRegisterCommand;

/**
 * @property BaseUserApplicationService $service
 */
trait LoginActions
{

    public function captcha(Request $request) : JsonResponse
    {
        $command = UserLoginCaptchaCommand::from($request);
        $this->service->loginCaptcha($command);
        return static::success();
    }

    public function login(Request $request) : JsonResponse
    {
        if (!$request->input('fallback_register', false)) {
            $command       = UserLoginCommand::from($request);
            $userTokenData = $this->service->login($command);
        } else {
            $command       = UserLoginOrRegisterCommand::from($request);
            $userTokenData = $this->service->loginOrRegister($command);
        }
        return static::success($userTokenData->toArray());

    }
}