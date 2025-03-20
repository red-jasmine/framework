<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Casts\AmountCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasGenerateNo;
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

    use HasGenerateNo;

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


    protected string $generateNoKey = 'transaction_no';

    public function generateNoFactors() : array
    {
        // 24位 + 2位 收支类型 + 2位 应用 + 2位 钱包ID
        return [
            '01',
            $this->factorRemainder($this->direction->value),
            $this->factorRemainder($this->app_id),
            $this->factorRemainder($this->wallet_id),
        ];
    }

}
