<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries;

use RedJasmine\Support\Contracts\UserInterface;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class FindCartQuery extends FindQuery
{
    public UserInterface $owner;
} 