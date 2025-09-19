<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * 组织的根部门（无父级部门）
     */
    public function rootDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'org_id')
            ->where('parent_id', 0);
    }

    /**
     * 组织的在职成员集合
     */
    public function activeMembers(): HasMany
    {
        return $this->hasMany(Member::class, 'org_id')
            ->where('status', 'active');
    }
}


