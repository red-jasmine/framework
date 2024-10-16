<?php

namespace RedJasmine\Card\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Card\Domain\Enums\CardStatus;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Card extends Model implements OwnerInterface, OperatorInterface
{

    use HasSnowflakeId;

    use HasOwner;

    use HasOperator;

    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'group_id',
        'status',
        'content',
        'remarks',
        'is_loop',
    ];

    public function getTable() : string
    {
        return config('red-jasmine-card.tables.prefix') . 'cards';
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(CardGroup::class, 'group_id', 'id');
    }

    protected function casts() : array
    {
        return [
            'status'    => CardStatus::class,
            'is_loop'   => 'boolean',
            'sold_time' => 'datetime'
        ];
    }


    public function scopeEnable(Builder $query) : Builder
    {
        return $query->where('status', CardStatus::ENABLE);
    }

}
