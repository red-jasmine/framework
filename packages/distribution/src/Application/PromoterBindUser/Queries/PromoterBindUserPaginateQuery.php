<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PromoterBindUserPaginateQuery extends PaginateQuery
{
    /**
     * 分销员ID
     */
    public ?int $promoterId = null;

    /**
     * 用户类型
     */
    public ?string $userType = null;

    /**
     * 用户ID
     */
    public ?int $userId = null;

    /**
     * 状态
     */
    public ?string $status = null;
} 