<?php

namespace RedJasmine\Support\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use RedJasmine\Support\Http\ResponseJson;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use ResponseJson;
    use UserOwnerTools;
}
