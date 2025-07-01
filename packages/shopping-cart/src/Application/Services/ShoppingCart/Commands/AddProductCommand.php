<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProduct;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class AddProductCommand extends Data
{
    public string $market = 'default';

    public UserInterface $owner;

    public CartProduct $product;

    public int $quantity = 1;

    public array $properties = [];
} 