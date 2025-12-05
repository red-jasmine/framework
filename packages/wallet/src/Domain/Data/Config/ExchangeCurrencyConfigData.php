<?php

namespace RedJasmine\Wallet\Domain\Data\Config;

use RedJasmine\Support\Foundation\Data\Data;

class ExchangeCurrencyConfigData extends Data
{

    /**
     * 币种
     * @var string
     */
    public string $currency;

    /**
     * 汇率
     */
    public string $exchangeRate;


    /**
     * 手续费比例
     */
    public string $feeRate;
}