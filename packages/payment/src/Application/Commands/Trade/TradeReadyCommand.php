<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use RedJasmine\Payment\Domain\Data\PaymentEnvironmentData;

/**
 * 支付准备
 * 通过支付准备命令，返回可选的支付平台如：微信、支付宝、银联等
 */
class TradeReadyCommand extends PaymentEnvironmentData
{
    public int $id;




}
