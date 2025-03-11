<?php

namespace RedJasmine\Payment\Application\Services\Payer\Commands;

use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Support\Application\CommandHandler;

class PayerLoginCommandHandler extends CommandHandler
{


    public function handle(PayerLoginCommand $command) : Payer
    {
        // TODO 内置 H5 和 小程序

        // 根据当前环境，当前应用的授权

        return new Payer();

    }
}