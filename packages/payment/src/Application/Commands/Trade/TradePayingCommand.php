<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

/**
 * 发起支付
 * 这些因素是会 决定可选的 支付方式
 */
class TradePayingCommand extends Environment
{
    public int $id;

}
