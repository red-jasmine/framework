<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;

class WalletRecharge extends Model implements OwnerInterface, OperatorInterface
{

    use HasOwner;

    use HasDateTimeFormatter;

    use HasOperator;

    public $incrementing = false;

    protected $casts = [
        'status'               => RechargeStatusEnum::class,
        'extra'                => 'array',
        'amount'               => MoneyCast::class, ':currency,total',// 充值金额
        'payment_status'       => PaymentStatusEnum::class,
        'payment_amount'       => MoneyCast::class, ':payment_currency,payment_amount',// 支付金额
        'payment_fee'          => MoneyCast::class, ':payment_currency,payment_fee',// 支付金额
        'total_payment_amount' => MoneyCast::class, ':payment_currency,total_payment_amount',// 支付总金额
    ];


    public function wallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
