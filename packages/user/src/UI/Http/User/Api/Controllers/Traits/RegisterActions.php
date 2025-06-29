<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers\Traits;


use Illuminate\Http\JsonResponse;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Application\Services\Commands\UserRegisterCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\UserRegisterCommand;
use RedJasmine\User\UI\Http\User\Api\Requests\RegisterRequest;

/**
 * @property BaseUserApplicationService $service
 */
trait RegisterActions
{


    /**
     * @param  RegisterRequest  $request
     *
     * @return JsonResponse
     */
    public function captcha(RegisterRequest $request) : JsonResponse
    {
        $command = UserRegisterCaptchaCommand::from($request);


        $this->service->registerCaptcha($command);

        return static::success();

    }

    public function register(RegisterRequest $request) : JsonResponse
    {

        $command = UserRegisterCommand::from($request);


        $userTokenData = $this->service->register($command);

        return static::success($userTokenData->toArray());

    }

}