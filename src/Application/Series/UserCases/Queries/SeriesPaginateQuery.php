<?php

namespace RedJasmine\Product\Application\Series\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class SeriesPaginateQuery extends PaginateQuery
{
    public ?string $name;
    public ?string $ownerType;
    public ?int    $ownerId;

}
