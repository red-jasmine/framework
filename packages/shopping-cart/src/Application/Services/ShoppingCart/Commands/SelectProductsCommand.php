<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\ProductIdentity;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class SelectProductsCommand extends Data
{
    public UserInterface $owner;
    /** @var ProductIdentity[] */
    public array $products;
    public bool  $selected = true;
} 