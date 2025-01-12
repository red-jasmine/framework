<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Support\Data\Data;

class ChannelTransferCreateCommand extends Data
{
    public string $transferNo;
}
