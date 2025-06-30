<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class SelectProductsCommand extends Data
{
    public UserInterface $owner;
    /** @var CartProductIdentity[] */
    public array $identities;
    public bool $selected = true;
} 