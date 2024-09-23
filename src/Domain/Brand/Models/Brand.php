<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\ModelTree;
use RedJasmine\Support\Helpers\ID\Snowflake;


class Brand extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;


    use ModelTree;

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_id';
    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'sort';

    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';


    protected $casts = [
        'is_show' => 'boolean',
        'status'  => BrandStatusEnum::class
    ];


    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'english_name',
        'initial',
        'is_show',
        'status',
        'logo',
        'sort',
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
        return $query->where('status', BrandStatusEnum::ENABLE->value);
    }


    public function create() : static
    {
        $this->id = Snowflake::getInstance()->nextId();
        return $this;
    }

    public function modify() : static
    {

        return $this;
    }

    public function isAllowUse() : bool
    {
        return $this->status === BrandStatusEnum::ENABLE;
    }

}
