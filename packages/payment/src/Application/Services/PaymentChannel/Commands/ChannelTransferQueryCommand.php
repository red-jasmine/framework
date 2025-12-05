<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class ChannelTransferQueryCommand extends Data
{
    public string $transferNo;
}
