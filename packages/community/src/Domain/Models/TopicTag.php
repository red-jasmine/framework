<?php

namespace RedJasmine\Community\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Community\Domain\Models\Enums\TagStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class TopicTag extends Model implements OwnerInterface, OperatorInterface
{

    use HasOperator;

    use HasOwner;

    public $incrementing = false;

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;


    protected $casts = [
        'is_public' => 'boolean',
        'is_show'   => 'boolean',
        'status'    => TagStatusEnum::class
    ];

    protected $fillable = [
        'owner_type',
        'owner_id',
        'name',
        'description',
        'cluster',
        'icon',
        'color',
        'sort',
        'status',
        'is_show',
        'is_public',
    ];


    public function scopeEnable(Builder $query) : Builder
    {
        return $query->where('status', TagStatusEnum::ENABLE);
    }

    public function scopeShow(Builder $query) : Builder
    {
        return $query->where('is_show', true)->enable();
    }
}
