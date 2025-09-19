<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Models\Enums\OrganizationStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Organization extends Model
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
            $instance->status = OrganizationStatusEnum::ENABLE;
            $instance->setUniqueIds();
        }
        return $instance;
    }

    protected function casts(): array
    {
        return [
            'status' => OrganizationStatusEnum::class,
        ];
    }
}


