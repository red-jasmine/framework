<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Data\Data;

class TransferCreateData extends Data
{
    public int $merchantAppId;

    /**
     * 内部渠道应用ID
     * @var int
     */
    public int $channelAppId;

    public ?string $merchantTransferNo;

    public string $subject;

    public ?string $description;

    public string $sceneCode;

    public Money $amount;

    public TransferPayee $payee;


    public string $payer;
}
