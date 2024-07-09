<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Helpers\ID\Snowflake;


class Brand extends Model implements OperatorInterface
{

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;


    use \RedJasmine\Support\Domain\Models\Traits\ModelTree;

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_id';
    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'sort';

    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';


    public $incrementing = false;

    protected $casts = [
        'expands' => 'array',
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
        'expands',
        'logo',
        'sort',
    ];


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
