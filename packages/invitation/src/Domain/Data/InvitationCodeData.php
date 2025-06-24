<?php

namespace RedJasmine\Invitation\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Invitation\Domain\Models\Enums\GenerateType;
use RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 邀请码数据
 */
class InvitationCodeData extends Data
{
    /**
     * 邀请人信息
     */
    public Inviter $inviter;

    /**
     * 邀请码
     */
    public ?string $code = null;

    /**
     * 生成类型
     */
    #[WithCast(EnumCast::class, GenerateType::class)]
    public GenerateType $generateType = GenerateType::SYSTEM;

    /**
     * 过期时间
     */
    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $expiredAt = null;

    /**
     * 最大使用次数
     */
    public ?int $maxUsages = null;

    /**
     * 标签
     */
    public array $tags = [];

    /**
     * 备注
     */
    public ?string $remarks = null;
} 