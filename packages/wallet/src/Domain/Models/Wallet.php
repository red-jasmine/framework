<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Wallet\Domain\Models\Enums\WalletStatusEnum;


class Wallet extends Model implements OperatorInterface, OwnerInterface
{

    public $incrementing = false;

    public $uniqueShortId = true;

    use HasSnowflakeId;


    use HasOperator;

    use HasOwner;

    use HasDateTimeFormatter;


    protected $fillable = [
        'owner_type',
        'owner_id',
        'type',
    ];
    protected $casts    = [
        'status' => WalletStatusEnum::class
    ];

    public function transactions() : HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id', 'id');
    }
}
