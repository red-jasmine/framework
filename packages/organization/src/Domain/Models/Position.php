<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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


}


