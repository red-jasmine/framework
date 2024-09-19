<?php

namespace RedJasmine\Product\Application\Series\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class SeriesPaginateQuery extends PaginateQuery
{
    public ?string $name;
    public ?string $ownerType;
    public ?int    $ownerId;

}
