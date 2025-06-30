<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Application\Queries\Query;

class ListCartProductsQuery extends Query
{
    public UserInterface $owner;
    public ?string $cartId = null;
} 