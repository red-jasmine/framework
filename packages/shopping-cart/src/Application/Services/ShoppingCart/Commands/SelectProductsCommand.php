<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProduct;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class SelectProductsCommand extends Data
{
    public UserInterface $owner;
    /** @var CartProduct[] */
    public array $identities;
    public bool $selected = true;
} 