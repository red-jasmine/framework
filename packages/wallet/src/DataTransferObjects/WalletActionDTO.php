<?php

namespace RedJasmine\Wallet\DataTransferObjects;

use RedJasmine\Support\Data\Data;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;

class WalletActionDTO extends Data
{

    /**
     * @var TransactionTypeEnum|null
     */
    public ?TransactionTypeEnum $type;

    /**
     * 金额
     * @var string|int|float
     */
    public string|int|float $amount;


    public string $title;

    public ?string $description = null;

    public ?string $billType = null;

    public ?string $businessId = null;

}
