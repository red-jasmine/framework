<?php

namespace RedJasmine\Product\Domain\Group\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Group\Models\Enums\GroupStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\ModelTree;

class ProductGroup extends Model implements OperatorInterface, OwnerInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    use ModelTree;

    use SoftDeletes;


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
        'status',
        'is_leaf',
        'is_show',
    ];

    protected $casts = [
        'status'  => GroupStatusEnum::class,
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
        return $query->where('status', GroupStatusEnum::ENABLE->value);
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

        if ($this->status !== GroupStatusEnum::ENABLE) {
            return false;
        }
        // TODO 所有上级是否支持使用
        return true;
    }

}

