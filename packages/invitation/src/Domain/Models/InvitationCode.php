<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Invitation\Domain\Events\InvitationCodeCreated;
use RedJasmine\Invitation\Domain\Events\InvitationCodeUsed;
use RedJasmine\Invitation\Domain\Models\Enums\CodeStatus;
use RedJasmine\Invitation\Domain\Models\Enums\GenerateType;
use RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter;
use RedJasmine\Invitation\Domain\Models\ValueObjects\InvitationTag;
use Illuminate\Database\Eloquent\Model;

/**
 * 邀请码聚合根
 */
final class InvitationCode extends Model
{
    protected $table = 'invitation_codes';

    /**
     * 模型事件映射
     */
    protected $dispatchesEvents = [
        'created' => InvitationCodeCreated::class,
    ];

    protected $fillable = [
        'code',
        'inviter_type',
        'inviter_id', 
        'inviter_name',
        'title',
        'description',
        'slogan',
        'generate_type',
        'max_usage',
        'used_count',
        'expires_at',
        'status',
        'tags',
        'extra_data',
    ];

    protected $casts = [
        'generate_type' => GenerateType::class,
        'status' => CodeStatus::class,
        'max_usage' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
        'tags' => 'array',
        'extra_data' => 'array',
    ];

    protected $attributes = [
        'generate_type' => GenerateType::SYSTEM,
        'status' => CodeStatus::ACTIVE,
        'max_usage' => 0,
        'used_count' => 0,
    ];

    /**
     * 获取邀请人信息
     */
    public function getInviterAttribute(): Inviter
    {
        return new Inviter(
            type: $this->inviter_type,
            id: $this->inviter_id,
            name: $this->inviter_name
        );
    }

    /**
     * 设置邀请人信息
     */
    public function setInviterAttribute(Inviter $inviter): void
    {
        $this->inviter_type = $inviter->type;
        $this->inviter_id = $inviter->id;
        $this->inviter_name = $inviter->name;
    }

    /**
     * 获取标签集合
     */
    public function getTagsCollectionAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }

        return array_map(
            fn($tag) => InvitationTag::fromArray($tag),
            $this->tags
        );
    }

    /**
     * 设置标签集合
     * 
     * @param InvitationTag[] $tags
     */
    public function setTagsCollectionAttribute(array $tags): void
    {
        $this->tags = array_map(
            fn(InvitationTag $tag) => $tag->toArray(),
            $tags
        );
    }

    /**
     * 邀请去向配置
     */
    public function destinations(): HasMany
    {
        return $this->hasMany(InvitationDestination::class);
    }

    /**
     * 使用记录
     */
    public function usageLogs(): HasMany
    {
        return $this->hasMany(InvitationUsageLog::class);
    }

    /**
     * 统计信息
     */
    public function statistics(): HasMany
    {
        return $this->hasMany(InvitationStatistics::class);
    }

    /**
     * 创建邀请码
     */
    public static function create(
        string $code,
        Inviter $inviter,
        string $title,
        string $description = '',
        string $slogan = '',
        GenerateType $generateType = GenerateType::SYSTEM,
        int $maxUsage = 0,
        ?\DateTime $expiresAt = null,
        array $tags = [],
        array $extraData = []
    ): self {
        $invitationCode = new self();
        $invitationCode->code = $code;
        $invitationCode->inviter = $inviter;
        $invitationCode->title = $title;
        $invitationCode->description = $description;
        $invitationCode->slogan = $slogan;
        $invitationCode->generate_type = $generateType;
        $invitationCode->max_usage = $maxUsage;
        $invitationCode->expires_at = $expiresAt;
        $invitationCode->tags_collection = $tags;
        $invitationCode->extra_data = $extraData;

        return $invitationCode;
    }

    /**
     * 使用邀请码
     */
    public function use(): void
    {
        if (!$this->canUse()) {
            throw new \DomainException('邀请码不可用');
        }

        $this->used_count++;
        
        // 检查是否达到使用上限
        if ($this->max_usage > 0 && $this->used_count >= $this->max_usage) {
            $this->status = CodeStatus::DISABLED;
        }
    }

    /**
     * 禁用邀请码
     */
    public function disable(): void
    {
        if (!$this->status->canTransitionTo(CodeStatus::DISABLED)) {
            throw new \DomainException('当前状态无法转换为禁用状态');
        }

        $this->status = CodeStatus::DISABLED;
    }

    /**
     * 启用邀请码
     */
    public function enable(): void
    {
        if (!$this->status->canTransitionTo(CodeStatus::ACTIVE)) {
            throw new \DomainException('当前状态无法转换为启用状态');
        }

        $this->status = CodeStatus::ACTIVE;
    }

    /**
     * 设置过期
     */
    public function expire(): void
    {
        if (!$this->status->canTransitionTo(CodeStatus::EXPIRED)) {
            throw new \DomainException('当前状态无法转换为过期状态');
        }

        $this->status = CodeStatus::EXPIRED;
    }

    /**
     * 添加标签
     */
    public function addTag(InvitationTag $tag): void
    {
        $tags = $this->tags_collection;
        
        // 检查是否已存在相同名称的标签
        foreach ($tags as $existingTag) {
            if ($existingTag->name === $tag->name) {
                throw new \DomainException("标签 {$tag->name} 已存在");
            }
        }

        // 检查标签数量限制
        if (count($tags) >= 10) {
            throw new \DomainException('标签数量不能超过10个');
        }

        $tags[] = $tag;
        $this->tags_collection = $tags;
    }

    /**
     * 移除标签
     */
    public function removeTag(string $tagName): void
    {
        $tags = $this->tags_collection;
        $filteredTags = array_filter($tags, fn($tag) => $tag->name !== $tagName);
        $this->tags_collection = array_values($filteredTags);
    }

    /**
     * 更新使用统计
     */
    public function updateUsageStats(int $usedCount): void
    {
        $this->used_count = $usedCount;
        
        // 检查是否需要更新状态
        if ($this->max_usage > 0 && $this->used_count >= $this->max_usage) {
            $this->status = CodeStatus::DISABLED;
        }
    }

    /**
     * 是否有效
     */
    public function isValid(): bool
    {
        return $this->status === CodeStatus::ACTIVE && !$this->isExpired();
    }

    /**
     * 是否已过期
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * 是否可以使用
     */
    public function canUse(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // 检查使用次数限制
        if ($this->max_usage > 0 && $this->used_count >= $this->max_usage) {
            return false;
        }

        return true;
    }

    /**
     * 是否在宽限期内
     */
    public function isInGracePeriod(): bool
    {
        if (!$this->expires_at || !$this->isExpired()) {
            return false;
        }

        $graceHours = config('invitation.code.expire_grace_hours', 24);
        $graceDeadline = $this->expires_at->addHours($graceHours);

        return now()->isBefore($graceDeadline);
    }

    /**
     * 获取剩余使用次数
     */
    public function getRemainingUsageAttribute(): ?int
    {
        if ($this->max_usage === 0) {
            return null; // 无限制
        }

        return max(0, $this->max_usage - $this->used_count);
    }

    /**
     * 获取使用率
     */
    public function getUsageRateAttribute(): float
    {
        if ($this->max_usage === 0) {
            return 0.0;
        }

        return round(($this->used_count / $this->max_usage) * 100, 2);
    }

    /**
     * 检查是否达到最大使用次数
     */
    public function isMaxUsagesReached(): bool
    {
        return $this->max_usage && $this->used_count >= $this->max_usage;
    }

    /**
     * 检查用户是否已使用过此邀请码
     */
    public function hasBeenUsedBy(\RedJasmine\Support\Contracts\UserInterface $user): bool
    {
        return $this->usageLogs()
            ->where('user_type', get_class($user))
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * 记录使用日志
     */
    public function recordUsage(\RedJasmine\Support\Contracts\UserInterface $user, array $context = []): InvitationUsageLog
    {
        $usageLog = new InvitationUsageLog();
        $usageLog->invitation_code_id = $this->id;
        $usageLog->user_type = get_class($user);
        $usageLog->user_id = $user->id;
        $usageLog->used_at = now();
        $usageLog->context = $context;
        
        // 保存使用日志
        $usageLog->save();
        
        // 增加使用计数
        $this->increment('used_count');
        
        // 分发使用事件
        event(new InvitationCodeUsed($this, $usageLog));
        
        return $usageLog;
    }

    /**
     * 检查是否为活跃状态
     */
    public function isActive(): bool
    {
        return $this->status === CodeStatus::ACTIVE;
    }

    /**
     * 设置邀请码
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 设置邀请人
     */
    public function setInviter(Inviter $inviter): self
    {
        $this->inviter = $inviter;
        return $this;
    }

    /**
     * 设置生成类型
     */
    public function setGenerateType(GenerateType $generateType): self
    {
        $this->generate_type = $generateType;
        return $this;
    }

    /**
     * 设置过期时间
     */
    public function setExpiredAt(?\DateTime $expiredAt): self
    {
        $this->expired_at = $expiredAt;
        return $this;
    }

    /**
     * 设置最大使用次数
     */
    public function setMaxUsages(?int $maxUsages): self
    {
        $this->max_usages = $maxUsages;
        return $this;
    }

    /**
     * 设置状态
     */
    public function setStatus(CodeStatus $status): self
    {
        $this->status = $status;
        return $this;
    }
} 