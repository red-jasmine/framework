<?php

namespace RedJasmine\Address\Domain\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Address\Domain\Models\Enums\AddressStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class Address extends Model implements OwnerInterface, OperatorInterface
{


    use HasSnowflakeId;

    public $incrementing = false;

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
        'type',
        'is_default',
        'tag',
        'remarks',
        'latitude',
        'longitude',
        'status',
    ];

    protected function casts() : array
    {
        return [
            'contacts'     => 'encrypted',
            'phone'        => 'encrypted',
            'address'      => 'encrypted',
            'more_address' => 'encrypted',
            'is_default'   => 'boolean',
            'status'       => AddressStatusEnum::class,
        ];
    }


}
