<?php

namespace RedJasmine\Invitation\Domain\Models\ValueObjects;

use Carbon\Carbon;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 邀请码配置值对象
 */
class InvitationCodeConfig extends ValueObject
{
    /**
     * 邀请码类型
     */
    public InvitationCodeTypeEnum $codeType;

    /**
     * 自定义邀请码
     */
    public ?string $customCode = null;

    /**
     * 最大使用次数
     */
    public int $maxUsage = 0;

    /**
     * 过期时间
     */
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
     * 构造函数
     */
    public function __construct(
        InvitationCodeTypeEnum $codeType,
        ?string $customCode = null,
        int $maxUsage = 0,
        ?Carbon $expiredAt = null,
        ?array $extraData = null,
        ?string $description = null
    ) {
        $this->codeType = $codeType;
        $this->customCode = $customCode;
        $this->maxUsage = $maxUsage;
        $this->expiredAt = $expiredAt;
        $this->extraData = $extraData;
        $this->description = $description;
    }

    /**
     * 是否为自定义类型
     */
    public function isCustom(): bool
    {
        return $this->codeType === InvitationCodeTypeEnum::CUSTOM;
    }

    /**
     * 是否为系统生成类型
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

    /**
     * 获取邀请码（如果是自定义的话）
     */
    public function getCode(): ?string
    {
        return $this->isCustom() ? $this->customCode : null;
    }
} 