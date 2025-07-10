<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * 用户优惠券分页查询
 */
class UserCouponPaginateQuery extends PaginateQuery
{
    public ?int $coupon_id = null;
    public ?string $status = null;
    public ?string $user_type = null;
    public ?int $user_id = null;
    public ?int $order_id = null;
    public ?array $issue_time_between = null;
    public ?array $expire_time_between = null;
    public ?array $used_time_between = null;
    public ?bool $is_available = null;
    public ?bool $is_expired = null;
    public ?bool $is_used = null;
} 