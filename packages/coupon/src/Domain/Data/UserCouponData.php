<?php

namespace RedJasmine\Coupon\Domain\Data;

use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserCouponData extends Data
{
    public ?int $id = null;
    
    public UserInterface $owner;
    
    public int $couponId;
    
    public UserInterface $user;
    
    #[WithCast(EnumCast::class, UserCouponStatusEnum::class)]
    public UserCouponStatusEnum $status = UserCouponStatusEnum::AVAILABLE;
    
    public ?string $startAt = null;
    
    public ?string $endAt = null;
    
    public ?int $orderId = null;
    
    public ?string $orderType = null;
    
    public ?string $usedAt = null;
    
    public ?string $expiredAt = null;
    
    public ?string $remark = null;
} 