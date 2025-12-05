<?php

namespace RedJasmine\Distribution\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;

class PromoterBindUserData extends Data
{
   
    /**
     * 用户
     */
    public UserInterface $user;
    
    /**
     * 分销员ID
     */
    public int $promoterId;
    
    /**
     * 状态
     */
    #[WithCast(EnumCast::class, PromoterBindUserStatusEnum::class)]
    public PromoterBindUserStatusEnum $status;
    
    /**
     * 绑定时间
     */
    #[WithCast(DateTimeInterfaceCast::class)]
    public Carbon $bindTime;
    
    /**
     * 保护时间
     */
    #[WithCast(DateTimeInterfaceCast::class)]
    public Carbon $protectionTime;
    
    /**
     * 过期时间
     */
    #[WithCast(DateTimeInterfaceCast::class)]
    public Carbon $expirationTime;
} 