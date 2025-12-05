<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class ChannelRefundQueryCommand extends Data
{


    public function __construct(public string $refundNo)
    {
    }

}
