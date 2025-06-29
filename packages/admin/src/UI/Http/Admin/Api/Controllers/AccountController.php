<?php

namespace RedJasmine\Admin\UI\Http\Admin\Api\Controllers;

use RedJasmine\Admin\Application\Services\AdminApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\AccountActions;

class AccountController extends Controller
{
    use AccountActions;

    public function __construct(
        protected AdminApplicationService $service
    ) {

    }

}