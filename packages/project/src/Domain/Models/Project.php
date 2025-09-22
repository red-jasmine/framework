<?php

namespace RedJasmine\Project\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Project\Domain\Models\Enums\ProjectMemberStatus;
use RedJasmine\Project\Domain\Models\Enums\ProjectStatus;
use RedJasmine\Project\Domain\Models\Enums\ProjectType;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;

class Project extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'parent_id',
        'name',
        'short_name',
        'description',
        'code',
        'project_type',
        'status',
        'sort',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
        'status' => ProjectStatus::class,
        'project_type' => ProjectType::class,
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            // 保存时的业务逻辑
            if (empty($model->code)) {
                $model->code = $model->generateCode();
            }
        });
    }

    // 关联关系
    public function ownerRelation(): MorphTo
    {
        return $this->morphTo();
    }

    // 实现OwnerInterface的owner方法
    public function owner(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->ownerRelation,
            set: fn($value) => $value
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Project::class, 'parent_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(ProjectRole::class);
    }

    // 业务方法
    public function addMember(UserInterface $member): ProjectMember
    {
        $memberData = [
            'project_id' => $this->id,
            'member_type' => $member->getType(),
            'member_id' => $member->getID(),
            'status' => ProjectMemberStatus::PENDING,
            'joined_at' => now(),
        ];

        return $this->members()->create($memberData);
    }

    public function removeMember(UserInterface $member): bool
    {
        $projectMember = $this->members()
            ->where('member_type', $member->getType())
            ->where('member_id', $member->getID())
            ->whereNull('left_at')
            ->first();

        if ($projectMember) {
            $projectMember->update([
                'status' => ProjectMemberStatus::LEFT,
                'left_at' => now(),
            ]);
            return true;
        }

        return false;
    }


    public function hasPermission(UserInterface $member, string $permission): bool
    {
        $projectMember = $this->members()
            ->where('member_type', $member->getType())
            ->where('member_id', $member->getID())
            ->whereNull('left_at')
            ->first();

        if (!$projectMember) {
            return false;
        }

        return $projectMember->hasPermission($permission);
    }

    public function isActive(): bool
    {
        return $this->status === ProjectStatus::ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status === ProjectStatus::DRAFT;
    }

    public function isPaused(): bool
    {
        return $this->status === ProjectStatus::PAUSED;
    }

    public function isArchived(): bool
    {
        return $this->status === ProjectStatus::ARCHIVED;
    }

    public function activate(): bool
    {
        if ($this->isDraft()) {
            $this->update(['status' => ProjectStatus::ACTIVE]);
            return true;
        }
        return false;
    }

    public function pause(): bool
    {
        if ($this->isActive()) {
            $this->update(['status' => ProjectStatus::PAUSED]);
            return true;
        }
        return false;
    }

    public function archive(): bool
    {
        if (!$this->isArchived()) {
            $this->update(['status' => ProjectStatus::ARCHIVED]);
            return true;
        }
        return false;
    }

    protected function generateCode(): string
    {
        $baseCode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->name), 0, 6));
        $counter = 1;
        $code = $baseCode;

        while ($this->owner->projects()->where('code', $code)->exists()) {
            $code = $baseCode . $counter;
            $counter++;
        }

        return $code;
    }
}
