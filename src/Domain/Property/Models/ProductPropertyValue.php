<?php

namespace RedJasmine\Product\Domain\Property\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class ProductPropertyValue extends Model implements OperatorInterface
{

    use HasDateTimeFormatter;

    use HasOperator;

    public $incrementing = false;


    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', PropertyStatusEnum::ENABLE);
    }

    protected $fillable = [
        'vid',
        'pid',
        'name',
        'group_id',
        'status',
        'expands',
        'sort',
        'creator_type',
        'creator_id',
    ];


    protected $casts = [
        'expands' => 'array',
        'status'      => PropertyStatusEnum::class,
    ];


    public function group() : BelongsTo
    {
        return $this->belongsTo(ProductPropertyGroup::class, 'group_id', 'id');
    }


    public function property() : BelongsTo
    {
        return $this->belongsTo(ProductProperty::class, 'pid', 'id');
    }
}
