<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\Application\Services\CouponUsage\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * 优惠券使用记录分页查询
 */
class CouponUsagePaginateQuery extends PaginateQuery
{
    public ?int $coupon_id = null;
    public ?string $order_no = null;
    public ?string $user_type = null;
    public ?int $user_id = null;
    public ?string $cost_bearer_type = null;
    public ?int $cost_bearer_id = null;
    public ?array $used_at_between = null;
} 