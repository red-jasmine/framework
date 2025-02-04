<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\User\Application\Services\Commands\UserLoginCommand;
use RedJasmine\User\Application\Services\UserCommandService;

class LoginController extends Controller
{


    public function __construct(
        protected UserCommandService $commandService
    ) {
    }

    public function login(Request $request) : JsonResponse|JsonResource
    {
        $command = UserLoginCommand::from($request);


        $userTokenData = $this->commandService->login($command);


        return static::success($userTokenData->toArray());

    }

}
