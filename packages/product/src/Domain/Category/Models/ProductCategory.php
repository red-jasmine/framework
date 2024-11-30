<?php

namespace RedJasmine\Product\Domain\Category\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\ModelTree;

class ProductCategory extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOperator;

    use ModelTree;

    use SoftDeletes;

    public function getTable()
    {
        return config('red-jasmine-product.tables.prefix','jasmine_') . 'product_categories';
    }


    public $incrementing = false;

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_id';

    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'sort';

    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';


    protected $fillable = [
        'parent_id',
        'name',
        'cluster',
        'image',
        'sort',
        'is_leaf',
        'is_show',
        'status',

    ];

    protected $casts = [
        'status'  => CategoryStatusEnum::class,
        'is_leaf' => 'boolean',
        'is_show' => 'boolean',
    ];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }


    public function scopeShow(Builder $query) : Builder
    {
        return $query->enable()->where('is_show', true);
    }


    public function scopeEnable(Builder $query) : Builder
    {
        return $query->where('status', CategoryStatusEnum::ENABLE->value);
    }

    /**
     * 叶子目录
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeLeaf(Builder $query) : Builder
    {
        return $query->where('is_leaf', true);
    }

    /**
     * @return bool
     */
    public function isAllowUse() : bool
    {
        if ($this->is_leaf === false) {
            return false;
        }

        if ($this->status !== CategoryStatusEnum::ENABLE) {
            return false;
        }
        // TODO 所有上级是否支持使用
        return true;
    }

}
