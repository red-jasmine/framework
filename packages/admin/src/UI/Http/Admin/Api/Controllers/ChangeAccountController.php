<?php

namespace RedJasmine\Admin\UI\Http\Admin\Api\Controllers;

use RedJasmine\Admin\Application\Services\AdminApplicationService;
use RedJasmine\User\UI\Http\User\Api\Controllers\Traits\ChangeAccountActions;

class ChangeAccountController extends Controller
{
    use ChangeAccountActions;

    public function __construct(

        protected AdminApplicationService $service
    ) {

    }

}