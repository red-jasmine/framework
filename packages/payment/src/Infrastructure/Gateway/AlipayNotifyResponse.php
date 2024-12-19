<?php

namespace RedJasmine\Payment\Infrastructure\Gateway;

use Illuminate\Http\Response;
use RedJasmine\Payment\Domain\Gateway\NotifyResponseInterface;

class AlipayNotifyResponse implements NotifyResponseInterface
{
    public function success() : Response
    {
        return response('success', 200);
    }

    public function fail() : Response
    {
        return response('fail', 400);
    }


}
