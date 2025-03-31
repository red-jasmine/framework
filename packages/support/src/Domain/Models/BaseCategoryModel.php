<?php

namespace RedJasmine\Support\Domain\Models;


use RedJasmine\Order\Domain\Models\Model;
use RedJasmine\Support\Domain\Models\Enums\CategoryStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\ModelTree;

/**
 * @property int $id
 * @property string $name
 * @property string $cluster
 * @property string $image
 * @property int $sort
 * @property bool $is_leaf
 * @property bool $is_show
 * @property CategoryStatusEnum $status
 *
 */
abstract class BaseCategoryModel extends Model implements OperatorInterface
{
    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOperator;

    use ModelTree;

    use SoftDeletes;


    public bool $incrementing = false;

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_id';

    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'sort';

    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';


    protected array $fillable = [
        'parent_id',
        'name',
        'cluster',
        'image',
        'sort',
        'is_leaf',
        'is_show',
        'status',

    ];

    protected function casts() : array
    {
        return [
            'status'  => CategoryStatusEnum::class,
            'is_leaf' => 'boolean',
            'is_show' => 'boolean',
        ];
    }

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
     * @param  Builder  $query
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

        return true;
    }

}