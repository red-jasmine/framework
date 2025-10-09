<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountCaptchaCommand;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountChangeCommand;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountVerifyCommand;

/**
 * @property BaseUserApplicationService $service
 */
trait ChangeAccountActions
{

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