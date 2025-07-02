<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\ProductIdentity;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class UpdateQuantityCommand extends Data
{
    public UserInterface   $owner;
    public ProductIdentity $product;
    public int             $quantity;
} 