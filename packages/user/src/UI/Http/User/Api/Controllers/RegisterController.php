<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;


use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\RegisterActions;

class RegisterController extends Controller
{

    use RegisterActions;

    public function __construct(
        protected UserApplicationService $service
    ) {
    }


}
