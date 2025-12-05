<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class ChannelRefundCreateCommand extends Data
{

    public string $refundNo;

}
