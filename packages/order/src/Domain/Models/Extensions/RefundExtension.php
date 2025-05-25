<?php

namespace RedJasmine\Order\Domain\Models\Extensions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class RefundExtension extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    public $incrementing = false;


    protected $fillable = [];


    protected $casts = [
        'images' => 'array'
    ];

}
