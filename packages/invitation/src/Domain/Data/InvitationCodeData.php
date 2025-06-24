<?php

namespace RedJasmine\Invitation\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Invitation\Domain\Models\Enums\GenerateType;
use RedJasmine\Support\Contracts\UserInterface;
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
    public UserInterface $owner;

    /**
     * 邀请码
     */
    public ?string $code = null;


    public ?string $title;                      // 邀请标题

    public ?string $description;                      // 邀请描述

    public ?string $slogan;                      // 广告语

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