<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Support\Data\Data;

class ChannelRefundQueryCommand extends Data
{


    public function __construct(public string $refundNo)
    {
    }

}
