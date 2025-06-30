<?php

namespace RedJasmine\ShoppingCart\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;
use RedJasmine\Support\Data\Data;

class ShoppingCartProductData extends Data
{
    public CartProductIdentity $identity;
    public int                 $quantity       = 1;
    public Money               $price;
    public ?Money              $discountAmount = null;
    public array               $properties     = [];
} 