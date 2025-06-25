<?php

namespace RedJasmine\Invitation\Application\Services\Queries;

use RedJasmine\Support\Data\Data;

/**
 * 查找查询
 */
class FindQuery extends Data
{
    public int $id;

    public ?array $with = null;
} 