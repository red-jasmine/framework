<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class CategoryListQuery extends PaginateQuery
{
    public ?string $biz = null;
    public ?string $ownerType = null;
    public ?string $ownerId = null;
    public ?int $parentId = null;
    public ?string $name = null;
    public ?bool $isShow = null;
}
