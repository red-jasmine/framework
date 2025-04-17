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

    use HasSnowflakeId;

    use ModelTree;

    protected $primaryKey = 'code';
    protected $keyType    = 'string';

    public $timestamps   = false;
    public $incrementing = false;

    protected $casts = [
        'level' => RegionLevelEnum::class
    ];

    protected $fillable = [
        'parent_code',
        'name',
        'code',
        'level',
        'area_code',
    ];

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_code';
    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'code';
    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';

    public mixed $defaultParentId = 0;

}
