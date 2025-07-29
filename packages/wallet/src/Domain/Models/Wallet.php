<?php

namespace RedJasmine\Wallet\Domain\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Wallet\Domain\Models\Enums\WalletStatusEnum;


/**
 * @property Money $balance
 */
class Wallet extends Model implements OperatorInterface, OwnerInterface
{

    public $incrementing = false;

    public $uniqueShortId = true;

    use HasSnowflakeId;


    use HasOperator;

    use HasDateTimeFormatter;

    use HasOwner;


    protected $fillable = [
        'owner_type',
        'owner_id',
        'type',
        'currency',
    ];
    protected $casts    = [
        'status'  => WalletStatusEnum::class,
        'balance' => MoneyCast::class.':currency,balance',
        'freeze'  => MoneyCast::class.':currency,freeze',
    ];


    public function isAvailable() : bool
    {

        return ($this->status === WalletStatusEnum::ENABLE);

    }

    public function transactions() : HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id', 'id');
    }

    public function transaction(WalletTransaction $walletTransaction) : bool
    {
        if (!$this->relationLoaded('transactions')) {
            $this->setRelation('transactions', Collection::make());
        }

        $this->transactions->add($walletTransaction);

        return true;
    }
}
