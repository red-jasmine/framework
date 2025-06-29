<?php

namespace RedJasmine\Admin\UI\Http\Admin\Api\Controllers;


use RedJasmine\Admin\Application\Services\AdminApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\LoginActions;

class LoginController extends Controller
{

    use LoginActions;

    public function __construct(
        protected AdminApplicationService $service
    ) {

    }

}