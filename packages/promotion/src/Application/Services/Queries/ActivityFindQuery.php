<?php

namespace RedJasmine\Promotion\Application\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * 查找活动查询
 */
class ActivityFindQuery extends FindQuery
{
    public ?bool $withProducts = null;
    public ?bool $withParticipations = null;
}
