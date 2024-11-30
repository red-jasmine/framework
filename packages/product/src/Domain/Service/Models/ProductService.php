<?php

namespace RedJasmine\Product\Domain\Service\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Service\Models\Enums\ServiceStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ProductService extends Model implements OperatorInterface
{
    use HasOperator;

    use HasDateTimeFormatter;

    use SoftDeletes;

    /**
     * @return string
     */
    public function getTable() : string
    {
        return config('red-jasmine-product.tables.prefix','jasmine_') . Str::snake(Str::pluralStudly(class_basename($this)));
    }


    protected $casts = [
        'is_show' => 'boolean',
        'status'  => ServiceStatusEnum::class,
    ];

    protected $fillable = [
        'name',
        'description',
        'cluster',
        'icon',
        'color',
        'sort',
        'is_show',
        'status',

    ];
    public function scopeEnable(Builder $query) : Builder
    {
        return $query->where('status', ServiceStatusEnum::ENABLE);
    }

    public function scopeShow(Builder $query) : Builder
    {
        return $query->where('is_show', true)->enable();
    }
}
