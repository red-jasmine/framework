<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Channel extends Model
{
    public $incrementing  = false;
    public $uniqueShortId = false;

    use HasSnowflakeId;

    use SoftDeletes;


    protected function casts() : array
    {
        return [
            'status' => ChannelStatusEnum::class,
        ];
    }

    protected $fillable = [
        'code',
        'name',
        'status'
    ];

    public function getTable() : string
    {
        return 'payment_channels';
    }

    public function products() : HasMany
    {
        return $this->hasMany(ChannelProduct::class, 'channel_code', 'code');
    }


}
