<?php

namespace RedJasmine\Wallet\DataTransferObjects\Recharges;

use RedJasmine\Support\DataTransferObjects\Data;

class RechargePaymentDTO extends Data
{

    public ?string $paymentType;

    public ?int $paymentId;

    public ?string $paymentChannelTradeNo;

    public ?string $paymentMode;

}
