<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Queries;

use RedJasmine\Coupon\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * 用户优惠券分页查询
 */
class UserCouponPaginateQuery extends PaginateQuery
{


    public ?UserInterface        $user;
    public ?UserCouponStatusEnum $status         = null;
    public ?int               $couponId       = null;
    public ?DiscountLevelEnum $discountLevel = null;

    public bool $available = true;
} 