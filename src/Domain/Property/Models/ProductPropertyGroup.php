<?php

namespace RedJasmine\Product\Domain\Property\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class ProductPropertyGroup extends Model implements OperatorInterface
{

    use HasDateTimeFormatter;

    use HasOperator;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'status',
        'expands',
        'sort',
    ];

    protected $casts = [
        'expands' => 'array',
        'status'      => PropertyStatusEnum::class
    ];

    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', PropertyStatusEnum::ENABLE);
    }

}
