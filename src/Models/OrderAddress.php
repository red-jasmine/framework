<?php

namespace RedJasmine\Order\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithDTO;

class OrderAddress extends Model
{
    use WithDTO;

    use SoftDeletes;

    use HasDateTimeFormatter;

    use SoftDeletes;

    public $incrementing = false;


    protected $fillable = [
        'contacts',
        'mobile',
        'country',
        'province',
        'city',
        'district',
        'street',
        'country_id',
        'province_id',
        'city_id',
        'district_id',
        'street_id',
        'address',
        'zip_code',
        'lon',
        'lat',
        'extends',
    ];

    protected $casts = [
        'extends'  => 'array',
        'contacts' => 'encrypted',
        'mobile'   => 'encrypted',
        'address'  => 'encrypted',
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
            get: fn($value, $attributes) => implode([ $attributes['province'], $attributes['city'], $attributes['district'], $attributes['street'], $attributes['address'] ])
        );

    }
}
