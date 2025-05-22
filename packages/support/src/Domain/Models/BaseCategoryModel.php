<?php

namespace RedJasmine\Support\Domain\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
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
 * @property UniversalStatusEnum $status
 *
 */
abstract class BaseCategoryModel extends Model implements OperatorInterface
{
    use HasSnowflakeId;

    public $uniqueShortId = true;

    use HasDateTimeFormatter;

    use HasOperator;

    use ModelTree;

    use SoftDeletes;

    protected $withOperatorNickname = true;

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

    protected function casts() : array
    {
        return [
            'status'  => UniversalStatusEnum::class,
            'is_leaf' => 'boolean',
            'is_show' => 'boolean',
            'extra'   => 'array',
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
        return $query->where('status', UniversalStatusEnum::ENABLE->value);
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

        if ($this->status !== UniversalStatusEnum::ENABLE) {
            return false;
        }

        return true;
    }

}