<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

/**
 * 发起支付
 * 这些因素是会 决定可选的 支付方式
 */
class TradePayingCommand extends Environment
{
    // 两者必须存在一个
    public int     $merchantAppId;
    public ?int    $id      = null;
    public ?string $tradeNo = null;

}
