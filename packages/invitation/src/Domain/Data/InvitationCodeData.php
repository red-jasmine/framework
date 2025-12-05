<?php

namespace RedJasmine\Invitation\Domain\Data;

use Carbon\Carbon;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeStatusEnum;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeTypeEnum;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
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
     * 过期时间
     */
    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $expiredAt = null;

    /**
     * 扩展数据
     */
    public ?array $extra = null;

    /**
     * 描述
     */
    public ?string $description = null;
    

} 