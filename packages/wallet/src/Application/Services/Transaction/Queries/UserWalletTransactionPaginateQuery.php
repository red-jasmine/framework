<?php

namespace RedJasmine\Wallet\Application\Services\Transaction\Queries;


use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 *
 */
class UserWalletTransactionPaginateQuery extends PaginateQuery
{

    public string $walletType;

    /**
     *
     * @var AmountDirectionEnum|null
     */
    #[WithCast(EnumCast::class, AmountDirectionEnum::class)]
    public ?AmountDirectionEnum $direction;


    #[WithCast(EnumCast::class, TransactionTypeEnum::class)]
    public ?TransactionTypeEnum $transactionType;

}