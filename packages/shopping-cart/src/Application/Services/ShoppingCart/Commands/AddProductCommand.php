<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class AddProductCommand extends Data
{
    public UserInterface       $owner;
    public CartProductIdentity $identity;
    public int                 $quantity = 1;
    public array $properties = [];
} 