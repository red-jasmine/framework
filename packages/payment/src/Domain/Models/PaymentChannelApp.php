<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PaymentChannelApp extends Model implements OwnerInterface, OperatorInterface
{

    use HasOwner;


    public $incrementing = false;

    use HasSnowflakeId;


    use SoftDeletes;

    use HasOperator;


    protected $fillable = [
        'channel_code',
        'channel_merchant_id',
        'channel_app_id',
        'channel_public_key',
        'channel_app_public_key',
        'channel_app_private_key',
        'status',

    ];


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_apps';
    }

    protected $casts = [
        'status'                  => ChannelAppStatusEnum::class,
        'channel_public_key'      => AesEncrypted::class,
        'channel_app_public_key'  => AesEncrypted::class,
        'channel_app_private_key' => AesEncrypted::class,
    ];


    public function channel() : BelongsTo
    {
        return $this->belongsTo(PaymentChannel::class, 'channel', 'code');
    }

}
