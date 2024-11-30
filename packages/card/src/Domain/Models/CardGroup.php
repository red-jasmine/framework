<?php

namespace RedJasmine\Card\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class CardGroup extends Model implements OwnerInterface, OperatorInterface
{

    use HasSnowflakeId;

    use HasOwner;

    use HasOperator;


    public $incrementing = false;


    protected $fillable = [
        'name',
        'remarks',
        'content_template'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-card.tables.prefix','jasmine_') . 'card_groups';
    }

    public function cards() : HasMany
    {
        return $this->hasMany(Card::class, 'group_id', 'id');
    }

    public function products() : HasMany
    {
        return $this->hasMany(CardGroupBindProduct::class, 'group_id', 'id');
    }
}
