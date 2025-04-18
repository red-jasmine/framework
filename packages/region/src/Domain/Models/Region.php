<?php

namespace RedJasmine\Region\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Region\Domain\Enums\RegionLevelEnum;
use RedJasmine\Region\Enums\RegionLevel;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\ModelTree;


class Region extends Model
{

    use HasDateTimeFormatter;

    use ModelTree;

    protected string $defaultKeyName = 'code';

    protected $keyType = 'string';

    public $timestamps = false;


    protected function casts() : array
    {
        return [
            'level'        => RegionLevelEnum::class,
            'timezones'    => 'array',
            'translations' => 'array'
        ];
    }


    protected $fillable = [
        'parent_code',
        'name',
        'code',
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
    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';

    public mixed $defaultParentId = '0';

}
