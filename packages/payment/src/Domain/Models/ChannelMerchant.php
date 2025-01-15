<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\ChannelMerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ChannelMerchant extends Model implements OwnerInterface, OperatorInterface
{

    public $incrementing = false;


    use HasOwner;

    use HasSnowflakeId;


    use SoftDeletes;

    use HasOperator;

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_channel_merchants';
    }


    protected $fillable = [
        'owner_type',
        'owner_id',
        'channel_code',
        'channel_merchant_id',
        'channel_merchant_name',
        'is_sandbox',
        'type',
        'status',
    ];

    protected function casts() : array
    {
        return [
            'type'       => MerchantTypeEnum::class,
            'status'     => ChannelMerchantStatusEnum::class,
            'is_sandbox' => 'boolean'

        ];
    }

    public function channel() : BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_code', 'code');
    }

    /**
     * 是否可用
     * @return bool
     */
    public function isAvailable() : bool
    {
        return $this->status === ChannelMerchantStatusEnum::ENABLE;
    }

    /**
     * @return bool
     */
    public function isSandbox() : bool
    {
        return $this->is_sandbox;
    }
}
