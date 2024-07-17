<?php

namespace RedJasmine\Card\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

class CardGroupBindProduct extends Model implements OwnerInterface, OperatorInterface
{

    use HasOwner;

    use HasOperator;


    public $incrementing = false;


    protected $fillable = [
        'product_type',
        'product_id',
        'sku_id',
        'group_id'
    ];


    public function group() : BelongsTo
    {
        return $this->belongsTo(CardGroup::class, 'group_id', 'id');
    }

}
