<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Application\Services\Commands\UserLoginCommand;
use RedJasmine\User\Application\Services\Commands\UserLoginOrRegisterCommand;
use RedJasmine\User\Application\Services\UserCommandService;
use RedJasmine\User\UI\Http\User\Api\Resources\UserBaseResource;

class LoginController extends Controller
{


    public function __construct(
        protected UserCommandService $commandService
    ) {
    }

    public function login(Request $request) : JsonResponse|JsonResource
    {
        if ($request->input('fallback_register', false)) {
            $command       = UserLoginCommand::from($request);
            $userTokenData = $this->commandService->login($command);
        } else {
            $command       = UserLoginOrRegisterCommand::from($request);
            $userTokenData = $this->commandService->loginOrRegister($command);
        }
        return static::success($userTokenData->toArray());

    }

}
