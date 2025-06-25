<?php

namespace RedJasmine\Invitation\Application\Services\Queries;

use RedJasmine\Support\Data\Data;

/**
 * 分页查询
 */
class PaginateQuery extends Data
{
    public int $page = 1;

    public int $perPage = 15;

    public bool $withCount = true;

    public ?array $with = null;

    public ?array $filters = null;

    public ?array $sorts = null;

    public function isWithCount(): bool
    {
        return $this->withCount;
    }
} 