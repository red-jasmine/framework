<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\User\Application\Services\Commands\UserRegisterCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\UserRegisterCommand;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\UI\Http\User\Api\Requests\RegisterRequest;
use RedJasmine\User\UI\Http\User\Api\Resources\UserBaseResource;

class RegisterController extends Controller
{
    public function __construct(
        protected UserApplicationService $service
    ) {
    }


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
