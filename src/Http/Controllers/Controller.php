<?php

namespace RedJasmine\Support\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use RedJasmine\Support\Http\ResponseJson;


class Controller extends BaseController
{
    use ResponseJson;
    use UserOwnerTools;
}
