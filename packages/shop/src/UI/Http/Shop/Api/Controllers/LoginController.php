<?php

namespace RedJasmine\Shop\UI\Http\Shop\Api\Controllers;

use RedJasmine\Shop\Application\Services\ShopApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\LoginActions;

class LoginController extends Controller
{
    use LoginActions;

    public function __construct(
        protected ShopApplicationService $service
    ) {
    }
} 