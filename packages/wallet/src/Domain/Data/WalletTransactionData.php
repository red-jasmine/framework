<?php

namespace RedJasmine\Wallet\Domain\Data;


use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Amount;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class WalletTransactionData extends Data
{

    public string $appId = 'system';

    #[WithCast(EnumCast::class, AmountDirectionEnum::class)]
    public AmountDirectionEnum $direction;

    /**
     * @var TransactionTypeEnum
     */
    #[WithCast(EnumCast::class, TransactionTypeEnum::class)]
    public TransactionTypeEnum $transactionType;

    /**
     * @var Amount
     */
    public Amount $amount;


    public ?string $title = null;

    public ?string $description = null;

    public ?string $billType = null;

    public ?string $outTradeNo = null;
    public ?string $tags       = null;
    public ?string $remarks    = null;


    /**
     * 是否允许超额消费
     * @var bool
     */
    public bool $isAllowNegative = false;


}