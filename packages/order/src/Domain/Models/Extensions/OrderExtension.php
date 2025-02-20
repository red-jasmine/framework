<?php

namespace RedJasmine\Order\Domain\Models\Extensions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderExtension extends Model
{

    use HasDateTimeFormatter;

    use SoftDeletes;

    public $incrementing = false;
    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix','jasmine_') . 'orders_extension';
    }
    protected $casts = [
        'buyer_expands'       => 'array',
        'seller_expands'      => 'array',
        'other_expands'       => 'array',
        'form'                => 'array',
        'tools'               => 'array',
    ];
}
