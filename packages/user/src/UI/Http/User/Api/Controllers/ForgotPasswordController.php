<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\ForgotPasswordActions;

class ForgotPasswordController extends Controller
{

    use ForgotPasswordActions;

    public function __construct(
        protected UserApplicationService $service
    ) {
    }


}