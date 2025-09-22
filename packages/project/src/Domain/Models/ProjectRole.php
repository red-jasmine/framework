<?php

namespace RedJasmine\Project\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Project\Domain\Models\Enums\ProjectMemberStatus;
use RedJasmine\Project\Domain\Models\Enums\ProjectRoleStatus;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ProjectRole extends Model
{
    use HasSnowflakeId;

    public $incrementing = false;

    protected $fillable = [
        'project_id',
        'name',
        'code',
        'description',
        'is_system',
        'permissions',
        'sort',
        'status',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system' => 'boolean',
        'status' => ProjectRoleStatus::class,
    ];

    // 关联关系
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    // 业务方法
    public function hasPermission(string $permission): bool
    {
        return $this->permissions && in_array($permission, $this->permissions);
    }

    public function addPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            return $this->update(['permissions' => $permissions]);
        }
        return true;
    }

    public function removePermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        $key = array_search($permission, $permissions);
        if ($key !== false) {
            unset($permissions[$key]);
            return $this->update(['permissions' => array_values($permissions)]);
        }
        return true;
    }

    public function isActive(): bool
    {
        return $this->status === ProjectRoleStatus::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === ProjectRoleStatus::INACTIVE;
    }

    public function isSystem(): bool
    {
        return $this->is_system;
    }

    public function activate(): bool
    {
        if ($this->isInactive()) {
            return $this->update(['status' => ProjectRoleStatus::ACTIVE]);
        }
        return false;
    }

    public function deactivate(): bool
    {
        if ($this->isActive()) {
            return $this->update(['status' => ProjectRoleStatus::INACTIVE]);
        }
        return false;
    }

    public function getActiveMembersCount(): int
    {
        return $this->members()
            ->where('status', ProjectMemberStatus::ACTIVE)
            ->whereNull('left_at')
            ->count();
    }

    public function canBeDeleted(): bool
    {
        // 系统角色不能删除
        if ($this->is_system) {
            return false;
        }

        // 有活跃成员的角色不能删除
        if ($this->getActiveMembersCount() > 0) {
            return false;
        }

        return true;
    }
}
