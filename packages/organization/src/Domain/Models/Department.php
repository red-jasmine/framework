<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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

    /**
     * 部门的主管理员（通过部门管理员中间表，条件：is_primary=true）
     */
    public function primaryManager(): HasOneThrough
    {
        return $this->hasOneThrough(
            Member::class,
            DepartmentManager::class,
            'department_id',
            'id',
            'id',
            'member_id'
        )
            ->where('department_managers.is_primary', true);
    }

    /**
     * 部门的管理员集合（通过部门管理员中间表）
     */
    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(
            Member::class,
            'department_managers',
            'department_id',
            'member_id'
        )
            ->using(DepartmentManager::class)
            ->withPivot(['is_primary'])
            ->withTimestamps();
    }

    /**
     * 部门的成员集合（通过部门成员中间表）
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            Member::class,
            'member_departments',
            'department_id',
            'member_id'
        )
            ->using(MemberDepartment::class)
            ->withPivot(['is_primary'])
            ->withTimestamps();
    }

    /**
     * 获取主管理员
     */
    public function getPrimaryManagerAttribute(): ?Member
    {
        return $this->primaryManager;
    }

}


