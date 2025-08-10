<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Support\Application\Queries\FindQuery;

class AnnouncementFindQuery extends FindQuery
{
    public ?string $biz = null;
    public ?string $ownerType = null;
    public ?string $ownerId = null;
    public ?int $categoryId = null;
    public ?string $status = null;
    public ?string $approvalStatus = null;
}
