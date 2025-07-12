<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Queries;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * 用户优惠券查找查询
 */
class UserCouponFindQuery extends FindQuery
{

    protected string $primaryKey = 'coupon_no';

} 