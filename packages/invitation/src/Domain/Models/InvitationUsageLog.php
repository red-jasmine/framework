<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Invitation\Domain\Models\Enums\ActionType;
use RedJasmine\Invitation\Domain\Models\Enums\PlatformType;

/**
 * 邀请使用记录实体
 */
class InvitationUsageLog extends Model
{
    protected $table = 'invitation_usage_logs';

    protected $fillable = [
        'invitation_code_id',
        'invitation_code',
        'user_type',
        'user_id',
        'user_name',
        'visitor_id',
        'session_id',
        'action_type',
        'platform_type',
        'ip_address',
        'user_agent',
        'referer',
        'extra_data',
    ];

    protected $casts = [
        'action_type' => ActionType::class,
        'platform_type' => PlatformType::class,
        'extra_data' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = true;
    public $incrementing = true;

    /**
     * 所属邀请码
     */
    public function invitationCode(): BelongsTo
    {
        return $this->belongsTo(InvitationCode::class);
    }

    /**
     * 记录使用日志
     */
    public static function record(
        int $invitationCodeId,
        string $invitationCode,
        ActionType $actionType,
        PlatformType $platformType,
        ?string $userType = null,
        ?string $userId = null,
        ?string $userName = null,
        ?string $visitorId = null,
        ?string $sessionId = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $referer = null,
        array $extraData = []
    ): self {
        return self::create([
            'invitation_code_id' => $invitationCodeId,
            'invitation_code' => $invitationCode,
            'action_type' => $actionType,
            'platform_type' => $platformType,
            'user_type' => $userType,
            'user_id' => $userId,
            'user_name' => $userName,
            'visitor_id' => $visitorId,
            'session_id' => $sessionId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'referer' => $referer,
            'extra_data' => $extraData,
        ]);
    }

    /**
     * 获取用户标识
     */
    public function getUserIdentifier(): string
    {
        if ($this->user_id) {
            return "{$this->user_type}:{$this->user_id}";
        }

        return $this->visitor_id ?? $this->session_id ?? $this->ip_address ?? 'unknown';
    }

    /**
     * 是否为转化行为
     */
    public function isConversion(): bool
    {
        return $this->action_type->isConversion();
    }

    /**
     * 获取操作权重
     */
    public function getWeight(): int
    {
        return $this->action_type->weight();
    }
} 