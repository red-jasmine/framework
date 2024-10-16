<?php

namespace RedJasmine\Card\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Relations\MorphTos;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class CardGroupBindProduct extends Model implements OwnerInterface, OperatorInterface
{

    use HasSnowflakeId;

    use HasOwner;

    use HasOperator;


    public function getTable() : string
    {
        return config('red-jasmine-card.tables.prefix') . 'card_group_bind_products';
    }

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


    public static array $morphLabels = [];

    public static function morphLabel($name, $label) : void
    {
        static::$morphLabels[$name] = $label;
    }

    public function product() : MorphTo
    {
        return $this->morphTo('product', 'product_type', 'product_id');
    }


}
