<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\LoginActions;

class LoginController extends Controller
{


    use LoginActions;

    public function __construct(
        protected UserApplicationService $service
    ) {
    }


}
