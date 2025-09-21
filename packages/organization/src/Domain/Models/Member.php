<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use RedJasmine\Organization\Domain\Models\Enums\MemberStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Member extends Model
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;

    public $incrementing = false;

    protected $fillable = [
        'leader_id',
    ];

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

    /**
     * 成员所在的部门集合（通过中间表）
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(
            Department::class,
            'member_departments',
            'member_id',
            'department_id'
        )
            ->using(MemberDepartment::class)
            ->withPivot(['is_primary'])
            ->withTimestamps();
    }

    /**
     * 成员的主部门
     */
    public function primaryDepartment(): HasOneThrough
    {
        return $this->hasOneThrough(
            Department::class,
            MemberDepartment::class,
            'member_id',
            'id',
            'id',
            'department_id'
        )
            ->where('member_departments.is_primary', true);
    }


    /**
     * 职位
     *
     * @return BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    /**
     * 上级
     *
     * @return BelongsTo
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    /**
     * 下级
     *
     * @return HasMany
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Member::class, 'leader_id');
    }

    /**
     * 成员管理的部门集合（通过部门管理员中间表）
     */
    public function managedDepartments(): BelongsToMany
    {
        return $this->belongsToMany(
            Department::class,
            'department_managers',
            'member_id',
            'department_id'
        )
            ->using(DepartmentManager::class)
            ->withPivot(['is_primary'])
            ->withTimestamps();
    }

    /**
     * 获取主部门
     */
    public function getPrimaryDepartmentAttribute(): ?Department
    {
        return $this->primaryDepartment;
    }

    /**
     * 检查是否为某部门的管理员
     */
    public function isManagerOf(int $departmentId): bool
    {
        return $this->managedDepartments()
            ->where('department_id', $departmentId)
            ->exists();
    }

    /**
     * 检查是否为某部门的主管理员
     */
    public function isPrimaryManagerOf(int $departmentId): bool
    {
        return $this->managedDepartments()
            ->where('department_id', $departmentId)
            ->wherePivot('is_primary', true)
            ->exists();
    }

    /**
     * 获取上级
     */
    public function getLeaderAttribute(): ?Member
    {
        return $this->leader;
    }

    /**
     * 获取下级
     */
    public function getSubordinatesAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->subordinates;
    }

    /**
     * 检查是否为某成员的上级
     */
    public function isLeaderOf(int $memberId): bool
    {
        return $this->subordinates()
            ->where('id', $memberId)
            ->exists();
    }

    /**
     * 检查是否为某成员的下级
     */
    public function isSubordinateOf(int $memberId): bool
    {
        return $this->leader_id === $memberId;
    }

    /**
     * 设置上级
     */
    public function setLeader(?Member $leader): void
    {
        $this->leader_id = $leader?->id;
    }

    /**
     * 移除上级
     */
    public function removeLeader(): void
    {
        $this->leader_id = null;
    }

    /**
     * 获取所有上级（递归）
     */
    public function getAllLeaders(): \Illuminate\Database\Eloquent\Collection
    {
        $leaders = collect();
        $currentLeader = $this->leader;

        while ($currentLeader) {
            $leaders->push($currentLeader);
            $currentLeader = $currentLeader->leader;
        }

        return $leaders;
    }

    /**
     * 获取所有下级（递归）
     */
    public function getAllSubordinates(): \Illuminate\Database\Eloquent\Collection
    {
        $subordinates = collect();
        $directSubordinates = $this->subordinates;

        foreach ($directSubordinates as $subordinate) {
            $subordinates->push($subordinate);
            $subordinates = $subordinates->merge($subordinate->getAllSubordinates());
        }

        return $subordinates;
    }
}


