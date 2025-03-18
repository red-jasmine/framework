<?php

namespace RedJasmine\Wallet\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;

class WalletTransactionData extends Data
{

    public ?AmountDirectionEnum $direction;


    /**
     * @var TransactionTypeEnum|null
     */
    public ?TransactionTypeEnum $type;


    /**
     * 金额
     * @var string|int|float
     */
    public string|int|float $amount;


    public ?string $title;

    public ?string $description = null;

    public ?string $billType = null;

    public ?string $businessId = null;


}