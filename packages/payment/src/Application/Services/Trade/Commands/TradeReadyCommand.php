<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

use RedJasmine\Payment\Domain\Data\PaymentEnvironmentData;

/**
 * 支付准备
 * 通过支付准备命令，返回可选的支付方式如：微信、支付宝、银联等
 */
class TradeReadyCommand extends PaymentEnvironmentData
{
    // 两者必须存在一个
    public int     $merchantAppId;
    public ?string $tradeNo = null;


}
