<?php

namespace RedJasmine\Project\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use RedJasmine\Project\Domain\Models\Enums\ProjectMemberStatus;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ProjectMember extends Model
{
    use HasSnowflakeId;

    public $incrementing = false;

    protected $fillable = [
        'project_id',
        'member_type',
        'member_id',
        'status',
        'joined_at',
        'left_at',
        'invited_by_type',
        'invited_by_id',
        'permissions',
    ];

    protected $casts = [
        'status' => ProjectMemberStatus::class,
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'permissions' => 'array',
    ];

    // 关联关系
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function member(): MorphTo
    {
        return $this->morphTo();
    }


    public function inviter(): MorphTo
    {
        return $this->morphTo('invited_by');
    }

    // 业务方法
    public function hasPermission(string $permission): bool
    {
        // 检查个人权限覆盖
        if ($this->permissions && in_array($permission, $this->permissions)) {
            return true;
        }

        return false;
    }


    public function leave(): bool
    {
        if ($this->status !== ProjectMemberStatus::LEFT) {
            return $this->update([
                'status' => ProjectMemberStatus::LEFT,
                'left_at' => now(),
            ]);
        }
        return false;
    }

    public function activate(): bool
    {
        if ($this->status === ProjectMemberStatus::PENDING) {
            return $this->update([
                'status' => ProjectMemberStatus::ACTIVE,
                'joined_at' => now(),
            ]);
        }
        return false;
    }

    public function pause(): bool
    {
        if ($this->status === ProjectMemberStatus::ACTIVE) {
            return $this->update(['status' => ProjectMemberStatus::PAUSED]);
        }
        return false;
    }

    public function resume(): bool
    {
        if ($this->status === ProjectMemberStatus::PAUSED) {
            return $this->update(['status' => ProjectMemberStatus::ACTIVE]);
        }
        return false;
    }

    public function isActive(): bool
    {
        return $this->status === ProjectMemberStatus::ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === ProjectMemberStatus::PENDING;
    }

    public function isPaused(): bool
    {
        return $this->status === ProjectMemberStatus::PAUSED;
    }

    public function isLeft(): bool
    {
        return $this->status === ProjectMemberStatus::LEFT;
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
}
