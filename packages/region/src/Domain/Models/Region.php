<?php

namespace RedJasmine\Region\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Region\Domain\Enums\RegionTypeEnum;
use RedJasmine\Region\Enums\RegionLevel;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasDefaultConnection;
use RedJasmine\Support\Domain\Models\Traits\ModelTree;


/**
 * 行政区划模型
 *
 * @property string $code 代码
 * @property string|null $parent_code 父级编码
 * @property string $country_code 国家代码 ISO 3166-1 alpha-2
 * @property RegionTypeEnum $type 类型
 * @property string $name 名称
 * @property string|null $region 大区
 * @property int $level 树层级
 * @property int $tree_height
 */
class Region extends Model
{

    use HasDateTimeFormatter;

    use ModelTree;

    protected $primaryKey = 'code';

    public $incrementing = false;

    use HasDefaultConnection;


    protected string $defaultKeyName = 'code';

    protected $keyType = 'string';

    public $timestamps = true;


    protected function casts() : array
    {
        return [
            'type' => RegionTypeEnum::class,
        ];
    }


    protected $fillable = [
        'parent_code',
        'name',
        'code',
        'type',
        'level',
        'phone_code',
        'country_code',
    ];

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_code';
    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'code';
    protected string $sortType    = 'asc';
    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';

    public mixed $defaultParentId = '';


    public function scopeLevel(Builder $query, int $level = 3)
    {
        return $query->where('level', '<=', $level);
    }

}
