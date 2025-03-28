<?php

namespace RedJasmine\Product\Domain\Property\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ProductPropertyValue extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOperator;


    use SoftDeletes;

    public $incrementing = false;


    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', PropertyStatusEnum::ENABLE);
    }

    protected $fillable = [
        'vid',
        'pid',
        'name',
        'description',
        'group_id',
        'status',
        'sort'
    ];


    protected $casts = [
        'extras' => 'array',
        'status'  => PropertyStatusEnum::class,
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
