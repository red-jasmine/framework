<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Support\Application\Queries\FindQuery;

class CategoryFindQuery extends FindQuery
{
    public ?string $biz = null;
    public ?string $ownerType = null;
    public ?string $ownerId = null;
    public ?int $parentId = null;
    public ?bool $isShow = null;
}
