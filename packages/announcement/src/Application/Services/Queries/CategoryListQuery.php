<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Support\Application\Queries\PaginationQuery;

class CategoryListQuery extends PaginationQuery
{
    public ?string $biz = null;
    public ?string $ownerType = null;
    public ?string $ownerId = null;
    public ?int $parentId = null;
    public ?string $name = null;
    public ?bool $isShow = null;
}
