<?php

namespace RedJasmine\Admin\UI\Http\Admin\Api\Controllers;

use RedJasmine\Admin\Application\Services\AdminApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\RegisterActions;

class RegisterController extends Controller
{

    use RegisterActions;
    public function __construct(
        protected AdminApplicationService $service
    ) {

    }

}