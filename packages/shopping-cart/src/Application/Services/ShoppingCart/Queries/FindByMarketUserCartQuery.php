<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries;

use RedJasmine\Support\Contracts\UserInterface;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class FindByMarketUserCartQuery extends FindQuery
{
    public UserInterface $owner;

    public string $market = 'default';
} 