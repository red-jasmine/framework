<?php

namespace RedJasmine\Product\Domain\Attribute\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasDefaultConnection;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ProductAttributeValue extends Model implements OperatorInterface
{

    use HasDefaultConnection;

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOperator;


    use SoftDeletes;

    public $incrementing = false;


    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', ProductAttributeStatusEnum::ENABLE);
    }

    protected $fillable = [
        'vid',
        'aid',
        'name',
        'description',
        'group_id',
        'status',
        'sort'
    ];


    protected $casts = [
        'extra' => 'array',
        'status'  => ProductAttributeStatusEnum::class,
    ];


    public function group() : BelongsTo
    {
        return $this->belongsTo(ProductAttributeGroup::class, 'group_id', 'id');
    }


    public function attribute() : BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'aid', 'id');
    }
}
