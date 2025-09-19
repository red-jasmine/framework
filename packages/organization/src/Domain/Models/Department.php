<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Models\Enums\DepartmentStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\ModelTree;

class Department extends Model
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;
    use ModelTree;

    public $incrementing = false;

    protected $fillable = [];

    public function newInstance($attributes = [], $exists = false): static
    {
        /** @var static $instance */
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $instance->status = DepartmentStatusEnum::ENABLE;
            $instance->setUniqueIds();
        }
        return $instance;
    }

    protected function casts(): array
    {
        return [
            'status' => DepartmentStatusEnum::class,
        ];
    }
}


