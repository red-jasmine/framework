<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Queries;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class FindByOwnerQuery extends PaginateQuery
{
    public UserInterface $owner;

} 