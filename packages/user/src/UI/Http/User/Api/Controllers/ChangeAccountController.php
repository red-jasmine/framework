<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountChangeCommand;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountVerifyCommand;
use RedJasmine\User\Application\Services\UserApplicationService;

class ChangeAccountController extends Controller
{

    public function __construct(
        protected UserApplicationService $service
    ) {
    }

    public function captcha(Request $request) : JsonResponse
    {

        $command = ChangeAccountCaptchaCommand::from($request);

        $command->setKey($this->getOwner()->getID());

        $this->service->changeAccountCaptcha($command);

        return static::success();
    }

    public function verify(Request $request) : JsonResponse
    {

        $command = ChangeAccountVerifyCommand::from($request);

        $command->setKey($this->getOwner()->getID());

        $this->service->changeAccountVerify($command);

        return static::success();
    }

    public function change(Request $request) : JsonResponse
    {

        $command = ChangeAccountChangeCommand::from($request);

        $command->setKey($this->getOwner()->getID());

        $this->service->changeAccountChange($command);

        return static::success();
    }

}