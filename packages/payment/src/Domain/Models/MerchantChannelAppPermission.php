<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Enums\PermissionStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class MerchantChannelAppPermission extends Model
{

    public $incrementing = false;
    use HasSnowflakeId;
    use HasOperator;


    protected $fillable = [
        'merchant_id',
        'channel_app_id',
        'status'
    ];

    protected $casts = [
        'status' => PermissionStatusEnum::class
    ];


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_merchant_channel_app_permissions';
    }


    public function merchant() : BelongsTo
    {

        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function channelApp() : BelongsTo
    {

        return $this->belongsTo(ChannelApp::class, 'channel_app_id', 'id');
    }


}