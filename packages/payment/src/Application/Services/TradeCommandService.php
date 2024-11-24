<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Models\PaymentTrade;
use RedJasmine\Support\Application\ApplicationCommandService;

class TradeCommandService extends ApplicationCommandService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.trade.command';

    protected static string $modelClass = PaymentTrade::class;

}
