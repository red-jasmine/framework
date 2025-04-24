<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Application\Services\Commands\UserLoginCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\UserLoginCommand;
use RedJasmine\User\Application\Services\Commands\UserLoginOrRegisterCommand;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\UI\Http\User\Api\Resources\UserBaseResource;

class LoginController extends Controller
{


    public function __construct(
        protected UserApplicationService $service
    ) {
    }


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
