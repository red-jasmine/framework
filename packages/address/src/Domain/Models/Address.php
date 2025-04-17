<?php

namespace RedJasmine\Address\Domain\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;

class Address extends Model
{


    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOwner;

    use HasOperator;


    protected $table = 'address';


    protected $fillable = [
        'contacts',
        'phone',
        'country',
        'province',
        'city',
        'district',
        'street',
        'country_code',
        'province_code',
        'city_code',
        'district_code',
        'street_code',
        'address',
        'more_address',
        'company',
        'postcode',
        'sort',
        'type',
        'is_default',
        'tag',
        'remarks'
    ];

    protected function casts() : array
    {
        return [
            'contacts'     => 'encrypted',
            'phone'        => 'encrypted',
            'address'      => 'encrypted',
            'more_address' => 'encrypted',
            'is_default'   => 'boolean',
        ];
    }


}
