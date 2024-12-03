<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Data\Data;

class ChannelProductMode extends Data
{

    /**
     * 支付方式
     * @var string
     */
    public string $methodCode;


    /**
     * 支付平台
     * @var string
     */
    public string $platFromCode;

}
