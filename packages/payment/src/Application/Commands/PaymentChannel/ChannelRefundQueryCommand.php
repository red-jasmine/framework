<?php

namespace RedJasmine\Payment\Application\Commands\PaymentChannel;

use RedJasmine\Support\Data\Data;

class ChannelRefundQueryCommand extends Data
{


    public function __construct(public string $refundNo)
    {
    }

}
