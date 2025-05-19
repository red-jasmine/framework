<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class OrderAddress extends Model
{

    use HasSnowflakeId;

    use SoftDeletes;
    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;


    public $incrementing = false;



    protected $fillable = [
        'contacts',
        'phone',
        'country',
        'province',
        'city',
        'district',
        'street',
        'village',
        'country_code',
        'province_code',
        'city_code',
        'district_code',
        'street_code',
        'village_code',
        'address',
        'more_address',
        'company',
        'postcode',
        'sort',
        'tag',
        'latitude',
        'longitude',

    ];

    protected $casts = [
        'extra' => 'array',
        'contacts' => AesEncrypted::class,
        'phone' => AesEncrypted::class,
        'address' => AesEncrypted::class,
    ];

    protected $appends = [
        'full_address'
    ];


    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'id', 'id');
    }

    public function fullAddress() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => implode([
                $attributes['province'], $attributes['city'], $attributes['district'], $attributes['street'], $attributes['address']
            ])
        );

    }
}
