<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\PermissionStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Merchant extends Model
{


    public $incrementing = false;

    use HasSnowflakeId;

    use HasOwner;


    use HasOperator;

    use SoftDeletes;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'status',
        'name',
        'short_name',
        'type',
    ];

    public $casts = [

        'status' => MerchantStatusEnum::class,
    ];


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_merchants';
    }


    public function setStatus(MerchantStatusEnum $status) : void
    {

        $this->status = $status;

        $this->fireModelEvent('changeStatus', false);

    }


    public function channelApps() : BelongsToMany
    {
        return $this->belongsToMany(
            ChannelApp::class,
            config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_merchant_channel_app_permissions',
            'merchant_id',
            'channel_app_id',
        )->using(MerchantChannelAppPermission::class)
                    ->wherePivot('status', PermissionStatusEnum::ENABLE->value)
                    ->withTimestamps();
    }


    /**
     * @return ChannelApp[]
     */
    public function getAvailableChannelApps()
    {
        return $this->channelApps->filter(function (ChannelApp $channelApp) {
            return $channelApp->isAvailable();
        });

    }
}
