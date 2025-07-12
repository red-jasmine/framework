<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class UserCouponPaginateQuery extends PaginateQuery
{
    public string $ownerType;

    public string $ownerId;

    public bool $userVisible = true;
}