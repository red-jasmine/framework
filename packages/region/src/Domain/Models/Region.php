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

    public $timestamps = false;


    protected function casts() : array
    {
        return [
            'type'         => RegionTypeEnum::class,
            'timezones'    => 'array',
            'translations' => 'array'
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
        'timezones',
        'translations',
    ];

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_code';
    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'code';
    protected string $sortType    = 'asc';
    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';

    public mixed $defaultParentId = '0';


    public function scopeLevel(Builder $query, int $height = 3)
    {
        return $query->where('level', '<=', $height);
    }

}
