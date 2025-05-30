<?php

namespace RedJasmine\Product\Domain\Property\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasDefaultConnection;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ProductPropertyGroup extends Model implements OperatorInterface
{

    use HasDefaultConnection;

    use HasSnowflakeId;


    use HasDateTimeFormatter;

    use HasOperator;

    use SoftDeletes;



    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        'sort',
    ];

    protected $casts = [
        'status'  => PropertyStatusEnum::class
    ];

    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', PropertyStatusEnum::ENABLE);
    }

}
