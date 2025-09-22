<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class RemoveProductCommand extends Data
{
    public string $market = 'default';

    public ?UserInterface $buyer;
}


