<?php

namespace RedJasmine\Card\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Card\Domain\Enums\CardStatus;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

class Card extends Model implements OwnerInterface, OperatorInterface
{

    use HasOwner;

    use HasOperator;

    use SoftDeletes;

    public $incrementing = false;

    protected function casts() : array
    {
        return [
            'status'    => CardStatus::class,
            'is_loop'   => 'boolean',
            'sold_time' => 'datetime'
        ];
    }


    protected $fillable = [
        'product_type',
        'product_id',
        'sku_id',
        'status',
        'content',
        'remarks',
        'is_loop',
    ];

}
