<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel\Commands;

use RedJasmine\Support\Data\Data;

class ChannelTransferQueryCommand extends Data
{
    public string $transferNo;
}
