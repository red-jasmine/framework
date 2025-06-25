<?php

namespace RedJasmine\Invitation\Domain\Data;

use Carbon\Carbon;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeStatusEnum;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 邀请码数据传输对象
 */
class InvitationCodeData extends Data
{
    /**
     * 所有者（邀请人）
     */
    public UserInterface $owner;

    /**
     * 邀请码
     */
    public ?string $code = null;

    /**
     * 邀请码类型
     */
    #[WithCast(EnumCast::class, InvitationCodeTypeEnum::class)]
    public InvitationCodeTypeEnum $codeType = InvitationCodeTypeEnum::SYSTEM;

    /**
     * 邀请码状态
     */
    #[WithCast(EnumCast::class, InvitationCodeStatusEnum::class)]
    public InvitationCodeStatusEnum $status = InvitationCodeStatusEnum::ACTIVE;

    /**
     * 最大使用次数（0表示无限制）
     */
    public int $maxUsage = 0;

    /**
     * 已使用次数
     */
    public int $usedCount = 0;

    /**
     * 过期时间
     */
    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $expiredAt = null;

    /**
     * 扩展数据
     */
    public ?array $extraData = null;

    /**
     * 描述
     */
    public ?string $description = null;

    /**
     * 操作人
     */
    public ?UserInterface $operator = null;

    /**
     * 构造函数
     */
    public function __construct()
    {
        // 从配置中设置默认值
        $config = config('invitation.code');
        
        if ($config) {
            $this->maxUsage = $config['default_max_usage'] ?? 0;
            
            if (isset($config['default_expires_in_days']) && $config['default_expires_in_days'] > 0) {
                $this->expiredAt = Carbon::now()->addDays($config['default_expires_in_days']);
            }
        }
    }

    /**
     * 是否为自定义邀请码
     */
    public function isCustom(): bool
    {
        return $this->codeType === InvitationCodeTypeEnum::CUSTOM;
    }

    /**
     * 是否为系统生成邀请码
     */
    public function isSystem(): bool
    {
        return $this->codeType === InvitationCodeTypeEnum::SYSTEM;
    }

    /**
     * 是否有使用次数限制
     */
    public function hasUsageLimit(): bool
    {
        return $this->maxUsage > 0;
    }

    /**
     * 是否有过期时间
     */
    public function hasExpiry(): bool
    {
        return $this->expiredAt !== null;
    }
} 