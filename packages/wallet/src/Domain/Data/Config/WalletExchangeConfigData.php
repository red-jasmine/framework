<?php

namespace RedJasmine\Wallet\Domain\Data\Config;

use RedJasmine\Support\Data\Data;

class WalletExchangeConfigData extends Data
{
    /**
     * 状态配置
     * @var bool
     */
    public bool $state;


    /**
     * 支持的兑换货币
     * @var ExchangeCurrencyConfigData[]
     */
    public array $currencies = [];
}