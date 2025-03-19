<?php

namespace RedJasmine\Wallet\Domain\Data;


use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Amount;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;

class WalletTransactionData extends Data
{
    /**
     * @var TransactionTypeEnum
     */
    public TransactionTypeEnum $transactionType;

    /**
     * @var Amount
     */
    public Amount $amount;


    public ?string $title = null;

    public ?string $description = null;

    public ?string $billType = null;

    public ?string $orderNo = null;
    public ?string $tags    = null;
    public ?string $remarks = null;


    /**
     * 是否允许超额消费
     * @var bool
     */
    public bool $isAllowNegative = false;


}