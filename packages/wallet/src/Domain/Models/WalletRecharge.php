<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;
use RedJasmine\Support\Traits\Models\WithDTO;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;

class WalletRecharge extends Model
{
    use WithDTO;

    use HasOwner;

    use HasDateTimeFormatter;

    use HasOperator;

    public $incrementing = false;

    protected $casts = [
        'status' => RechargeStatusEnum::class,
        'extras' => 'array',
    ];


    public function wallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
