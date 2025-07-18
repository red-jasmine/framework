<?php

namespace RedJasmine\Payment\Application\Services\Payer;

use RedJasmine\Payment\Application\Services\Payer\Commands\PayerLoginCommandHandler;
use RedJasmine\Support\Foundation\Service\Service;

class PayerApplicationService extends Service
{

    protected static $macros = [
        'login' => PayerLoginCommandHandler::class
    ];

}