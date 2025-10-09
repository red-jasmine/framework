<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;


use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\AccountActions;

class AccountController extends Controller
{


    use AccountActions;

    public function __construct(
        protected UserApplicationService $service,
    ) {
    }


}
