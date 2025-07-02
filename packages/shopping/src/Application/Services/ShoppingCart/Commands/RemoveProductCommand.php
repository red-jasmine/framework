<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Commands;

use RedJasmine\Ecommerce\Domain\Data\ProductIdentity;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class RemoveProductCommand extends Data
{
    public UserInterface   $owner;
    public ProductIdentity $product;
} 