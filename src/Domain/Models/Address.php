<?php

namespace RedJasmine\Address\Domain\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
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
        'contacts', 'mobile',
        'country', 'province', 'city', 'district', 'street', 'type',
        'country_id', 'province_id', 'city_id', 'district_id', 'street_id',
        'address', 'zip_code', 'sort', 'tag', 'zip_code', 'remarks', 'group_id'
    ];

    protected $casts = [
        'contacts' => 'encrypted',
        'mobile'   => 'encrypted',
        'address'  => 'encrypted'
    ];


}
