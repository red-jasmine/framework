<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Organization\Domain\Models\Enums\OrganizationStatusEnum;
use RedJasmine\Organization\Domain\Models\Enums\OrganizationTypeEnum;
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
            $instance->type = OrganizationTypeEnum::COMPANY;
            $instance->status = OrganizationStatusEnum::ENABLE;
            $instance->setUniqueIds();
        }
        return $instance;
    }

    protected function casts(): array
    {
        return [
            'type' => OrganizationTypeEnum::class,
            'status' => OrganizationStatusEnum::class,
        ];
    }

    /**
     * 组织的部门集合
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'org_id');
    }

    /**
     * 组织的成员集合
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'org_id');
    }




}


