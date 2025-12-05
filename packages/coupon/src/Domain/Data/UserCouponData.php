<?php

namespace RedJasmine\Coupon\Domain\Data;

use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserCouponData extends Data
{
    public int           $couponId;

    public UserInterface $user;

    #[WithCast(EnumCast::class, UserCouponStatusEnum::class)]
    public UserCouponStatusEnum $status = UserCouponStatusEnum::AVAILABLE;

    public ?string $issueTime = null;

    public ?string $expireTime = null;

    public ?string $usedTime = null;

    public ?int $orderId = null;
} 