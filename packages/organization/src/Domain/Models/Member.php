<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Models\Enums\MemberStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Member extends Model
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
            $instance->status = MemberStatusEnum::ACTIVE;
            $instance->setUniqueIds();
        }
        return $instance;
    }

    protected function casts(): array
    {
        return [
            'status' => MemberStatusEnum::class,
            'hired_at' => 'datetime',
            'resigned_at' => 'datetime',
            'departments' => 'array',
        ];
    }
}


