<?php

namespace RedJasmine\Order\Domain\Models\Extensions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderExtension extends Model
{

    use HasDateTimeFormatter;

    use SoftDeletes;
    protected $table = 'orders_extension';

    public $incrementing = false;

    protected $casts = [
        'buyer_extra'  => 'array',
        'seller_extra' => 'array',
        'other_extra'  => 'array',
        'tools'        => 'array',
    ];
}
