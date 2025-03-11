<?php

namespace RedJasmine\Payment\Application\Services\Payer;

use RedJasmine\Payment\Application\Services\Payer\Commands\PayerLoginCommandHandler;
use RedJasmine\Support\Application\ApplicationService;

class PayerCommandService extends ApplicationService
{

    protected static $macros = [
        'login' => PayerLoginCommandHandler::class
    ];

}