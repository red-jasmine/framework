<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use RedJasmine\User\Application\Services\UserApplicationService;

class LoginController extends BaseLoginController
{


    public function __construct(
        protected UserApplicationService $service
    ) {
    }


}
