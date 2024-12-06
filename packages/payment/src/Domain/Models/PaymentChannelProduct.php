<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Data\ChannelProductModeData;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\PaymentChannelProductMode;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PaymentChannelProduct extends Model
{

    public $incrementing = false;
    use HasSnowflakeId;
    use SoftDeletes;

    use HasOperator;

    public static function newModel() : static
    {
        $model     = new static();
        $model->id = $model->newUniqueId();
        $model->setRelation('modes', Collection::make());
        return $model;
    }


    protected $fillable = [
        'channel_code',
        'rate',
        'name',
        'code',
        'status',
    ];


    protected $casts = [
        'status' => ChannelProductStatusEnum::class,
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_products';
    }

    public function channel() : BelongsTo
    {
        return $this->belongsTo(PaymentChannel::class, 'channel_code', 'code');
    }


    /**
     * @param ChannelProductModeData[] $modes
     * @return $this
     */
    public function setModes(array $modes) : static
    {
        foreach ($modes as $mode) {
            $modeModel                             = $this->modes->where('platform_code', $mode->platformCode)
                                                                 ->where('method_code', $mode->methodCode)
                                                                 ->first();

            $modeModel                             = $modeModel ?? new PaymentChannelProductMode;
            $modeModel->payment_channel_product_id = $this->id;
            $modeModel->platform_code              = $mode->platformCode;
            $modeModel->method_code                = $mode->methodCode;
            $modeModel->status                     = $mode->status;
            $this->modes->add($modeModel);
        }

        return $this;
    }

    public function modes() : HasMany
    {
        return $this->hasMany(PaymentChannelProductMode::class, 'payment_channel_product_id', 'id');
    }

}
