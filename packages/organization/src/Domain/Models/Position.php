<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Organization\Domain\Models\Enums\PositionStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Position extends Model
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;

    public $incrementing = false;

    protected $fillable = [];

    public function newInstance($attributes = [], $exists = false): static
    {
        /** @var static $instance */
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $instance->status = PositionStatusEnum::ENABLE;
            $instance->setUniqueIds();
        }
        return $instance;
    }

    protected function casts(): array
    {
        return [
            'status' => PositionStatusEnum::class,
        ];
    }

    /**
     * 成员职位中间表记录
     */
    public function memberPositions(): HasMany
    {
        return $this->hasMany(MemberPosition::class);
    }

    /**
     * 职位的成员集合（通过中间表）
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            Member::class,
            'member_positions',
            'position_id',
            'member_id'
        )
            ->using(MemberPosition::class)
            ->withTimestamps();
    }

}


