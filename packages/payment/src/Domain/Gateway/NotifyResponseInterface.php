<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Illuminate\Http\Response;

interface NotifyResponseInterface
{

    public function success() : Response;


    public function fail() : Response;

}
