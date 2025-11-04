<?php

namespace RedJasmine\Product\Domain\Attribute\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasDefaultConnection;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ProductAttribute extends Model implements OperatorInterface
{
    use HasDefaultConnection;

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOperator;

    use SoftDeletes;


    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'type',
        'unit',
        'is_allow_multiple',
        'is_allow_alias',
        'status',
        'group_id',
        'sort'
    ];

    protected $casts = [
        'is_allow_multiple' => 'boolean',
        'is_allow_alias'    => 'boolean',
        'type'              => ProductAttributeTypeEnum::class,
        'status'            => ProductAttributeStatusEnum::class
    ];

    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', ProductAttributeStatusEnum::ENABLE);
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(ProductAttributeGroup::class, 'group_id', 'id');
    }

    public function values() : HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'id', 'aid');
    }


    public function isAllowMultipleValues() : bool
    {
        return $this->is_allow_multiple;
    }


    public function isAllowAlias() : bool
    {
        return $this->is_allow_alias;
    }
}
