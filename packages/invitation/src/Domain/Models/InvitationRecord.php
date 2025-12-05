<?php

namespace RedJasmine\Invitation\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 邀请记录领域模型
 * 
 * @property int $id
 * @property int $invitation_code_id
 * @property string $inviter_type
 * @property int $inviter_id
 * @property string $invitee_type
 * @property int $invitee_id
 * @property string|null $invitee_nickname
 * @property array|null $context
 * @property string|null $target_url
 * @property string|null $target_type
 * @property array|null $rewards
 * @property Carbon $invited_at
 * @property Carbon|null $completed_at
 */
class InvitationRecord extends Model implements OperatorInterface
{
    use HasSnowflakeId;
    use HasOperator;
    use HasDateTimeFormatter;

    public $incrementing = false;

    protected $fillable = [
        'invitation_code_id',
        'inviter_type',
        'inviter_id',
        'invitee_type',
        'invitee_id',
        'invitee_nickname',
        'context',
        'target_url',
        'target_type',
        'rewards',
        'invited_at',
        'completed_at',
    ];

    /**
     * 类型转换配置
     */
    protected function casts(): array
    {
        return [
            'invitation_code_id' => 'integer',
            'inviter_id' => 'integer',
            'invitee_id' => 'integer',
            'context' => 'array',
            'rewards' => 'array',
            'invited_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * 关联邀请码
     */
    public function invitationCode(): BelongsTo
    {
        return $this->belongsTo(InvitationCode::class, 'invitation_code_id', 'id');
    }

    /**
     * 设置邀请人
     */
    public function setInviterAttribute(?UserInterface $inviter = null): void
    {
        if ($inviter) {
            $this->inviter_type = $inviter->getType();
            $this->inviter_id = $inviter->getID();
        }
    }

    /**
     * 获取邀请人
     */
    public function getInviterAttribute(): ?UserInterface
    {
        if ($this->inviter_type && $this->inviter_id) {
            return \RedJasmine\Support\Domain\Data\UserData::from([
                'type' => $this->inviter_type,
                'id' => $this->inviter_id,
            ]);
        }

        return null;
    }

    /**
     * 设置被邀请人
     */
    public function setInviteeAttribute(?UserInterface $invitee = null): void
    {
        if ($invitee) {
            $this->invitee_type = $invitee->getType();
            $this->invitee_id = $invitee->getID();
            $this->invitee_nickname = $invitee->getNickname();
        }
    }

    /**
     * 获取被邀请人
     */
    public function getInviteeAttribute(): ?UserInterface
    {
        if ($this->invitee_type && $this->invitee_id) {
            return \RedJasmine\Support\Domain\Data\UserData::from([
                'type' => $this->invitee_type,
                'id' => $this->invitee_id,
                'nickname' => $this->invitee_nickname,
            ]);
        }

        return null;
    }

    /**
     * 完成邀请
     */
    public function complete(?array $rewards = null): void
    {
        if ($this->isCompleted()) {
            return;
        }

        $this->rewards = $rewards;
        $this->completed_at = Carbon::now();
    }

    /**
     * 检查是否已完成
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * 获取邀请天数
     */
    public function getInvitedDaysAgo(): int
    {
        return $this->invited_at->diffInDays(Carbon::now());
    }

    /**
     * 获取完成天数
     */
    public function getCompletedDaysAgo(): ?int
    {
        if (!$this->isCompleted()) {
            return null;
        }

        return $this->completed_at->diffInDays(Carbon::now());
    }

    /**
     * 获取上下文信息
     */
    public function getContextValue(string $key, $default = null)
    {
        return data_get($this->context, $key, $default);
    }

    /**
     * 设置上下文信息
     */
    public function setContextValue(string $key, $value): void
    {
        $context = $this->context ?? [];
        data_set($context, $key, $value);
        $this->context = $context;
    }

    /**
     * 获取奖励信息
     */
    public function getRewardValue(string $key, $default = null)
    {
        return data_get($this->rewards, $key, $default);
    }

    /**
     * 设置奖励信息
     */
    public function setRewardValue(string $key, $value): void
    {
        $rewards = $this->rewards ?? [];
        data_set($rewards, $key, $value);
        $this->rewards = $rewards;
    }
} 