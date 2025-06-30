<?php

namespace RedJasmine\ShoppingCart\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class ShoppingCartData extends Data
{
    public string $market = 'default';

    public UserInterface $owner;

    /**
     * @var ShoppingCartProductData[]
     */
    public array $products;


} 