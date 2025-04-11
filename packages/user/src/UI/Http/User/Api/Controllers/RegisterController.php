<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use App\Http\Controllers\Controller;
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

    public function register(RegisterRequest $request) : UserBaseResource
    {

        $command = UserRegisterCommand::from($request);


        $user = $this->service->register($command);

        return UserBaseResource::make($user);


    }
}
