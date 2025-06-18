<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Queries;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class FindPromotersByOwnerQuery extends PaginateQuery
{
    public UserInterface $owner;
    
    public ?string $name = null;
} 