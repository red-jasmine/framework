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
     * @return JsonResponse|JsonResource
     */
    public function captcha(RegisterRequest $request) : JsonResponse|JsonResource
    {
        $command = UserRegisterCaptchaCommand::from($request);


        $this->service->registerCaptcha($command);

        return static::success();

    }

    public function register(RegisterRequest $request) : UserBaseResource
    {

        $command = UserRegisterCommand::from($request);


        $user = $this->service->register($command);

        return UserBaseResource::make($user);


    }
}
