<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers\Traits;


use Illuminate\Http\JsonResponse;
use RedJasmine\User\UI\Http\User\Api\Requests\RegisterRequest;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Application\Services\Commands\Register\UserRegisterCaptchaCommand;
use RedJasmine\UserCore\Application\Services\Commands\Register\UserRegisterCommand;

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