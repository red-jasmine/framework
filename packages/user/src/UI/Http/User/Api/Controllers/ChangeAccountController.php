<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountChangeCommand;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountVerifyCommand;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\ChangeAccountActions;

class ChangeAccountController extends Controller
{

    use ChangeAccountActions;

    public function __construct(
        protected UserApplicationService $service
    ) {
    }


}