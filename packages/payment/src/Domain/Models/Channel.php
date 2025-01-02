<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Channel extends Model
{

    public $incrementing = false;

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
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_channels';
    }

    public function products() : HasMany
    {
        return $this->hasMany(ChannelProduct::class, 'channel_code', 'code');
    }


    public function apps() : HasMany
    {
        return $this->hasMany(ChannelApp::class, 'channel_code', 'code');
    }


}
