<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Data\ChannelProductModeData;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductTypeEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelProductMode;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ChannelProduct extends Model
{

    public $incrementing = false;

    public $uniqueShortId = false;
    use HasSnowflakeId;
    use SoftDeletes;

    use HasOperator;


    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if ($exists === false) {
            $instance->setUniqueIds();
            $instance->setRelation('modes', Collection::make());
        }
        return $instance;
    }


    protected $fillable = [
        'channel_code',
        'type',
        'gateway',
        'name',
        'code',
        'status',
    ];


    protected function casts() : array
    {
        return [
            'status' => ChannelProductStatusEnum::class,
            'type'   => ChannelProductTypeEnum::class,
        ];
    }

    public function getTable() : string
    {
        return 'payment_channel_products';
    }

    public function channel() : BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_code', 'code');
    }


    /**
     * @param  ChannelProductModeData[]  $modes
     *
     * @return $this
     */
    public function setModes(array $modes) : static
    {

        foreach ($modes as $mode) {
            $modeModel = $this->modes->where('method_code', $mode->methodCode)
                                     ->where('scene_code', $mode->sceneCode)
                                     ->first();

            $modeModel                             = $modeModel ?? ChannelProductMode::make();
            $modeModel->system_channel_product_id = $this->id;
            $modeModel->method_code                = $mode->methodCode;
            $modeModel->scene_code                 = $mode->sceneCode;
            $modeModel->status                     = $mode->status;
            $this->modes->add($modeModel);
        }

        return $this;
    }

    public function modes() : HasMany
    {
        return $this->hasMany(ChannelProductMode::class, 'system_channel_product_id', 'id');
    }

    public function isAvailable() : bool
    {
        return $this->status === ChannelProductStatusEnum::ENABLE;
    }

}
