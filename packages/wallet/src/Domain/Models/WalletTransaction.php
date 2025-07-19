<?php

namespace RedJasmine\Wallet\Domain\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Support\Domain\Models\UniqueNoInterface;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;

/**
 * @property Money $amount
 * @property Money $balance_before
 * @property Money $freeze_before
 * @property Money $balance_after
 * @property Money $freeze_after
 */
class WalletTransaction extends Model implements OwnerInterface, OperatorInterface, UniqueNoInterface
{


    protected static string $uniqueNoPrefix = 'WT';

    protected static string $uniqueNoKey = 'transaction_no';
    use HasOwner;

    use HasOperator;

    use HasDateTimeFormatter;

    public $incrementing = false;

    use HasUniqueNo;

    use HasSnowflakeId;

    protected function casts() : array
    {
        return [
            'trade_time'       => 'datetime',
            'status'           => TransactionStatusEnum::class,
            'direction'        => AmountDirectionEnum::class,
            'transaction_type' => TransactionTypeEnum::class,
            'amount'           => MoneyCast::class.':currency,amount',
            'balance_before'   => MoneyCast::class.':currency,balance_before',
            'freeze_before'    => MoneyCast::class.':currency,freeze_before',
            'balance_after'    => MoneyCast::class.':currency,balance_after',
            'freeze_after'     => MoneyCast::class.':currency,freeze_after',
            'extra'            => 'array',
        ];
    }


}
