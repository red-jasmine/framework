<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class ChannelTransferCreateCommand extends Data
{
    public string $transferNo;
}
