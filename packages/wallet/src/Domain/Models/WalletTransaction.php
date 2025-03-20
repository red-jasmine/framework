<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Casts\AmountCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;

/**
 * @property Amount $amount
 */
class WalletTransaction extends Model implements OperatorInterface
{

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
            'amount'           => AmountCast::class
        ];
    }


    protected string $uniqueNoKey = 'transaction_no';


    public function newUniqueNo() : string
    {
        return implode('', [
            $this->generateDatetimeId(),
            '01',
            $this->factorRemainder($this->direction->value),
            $this->factorRemainder($this->app_id),
            $this->factorRemainder($this->wallet_id),
        ]);
    }


}
