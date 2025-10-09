<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

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