<?php

namespace RedJasmine\Shopping\Domain\Repositories;

use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ShoppingCartReadRepositoryInterface extends ReadRepositoryInterface
{
    public function findWithProducts(string $cartId) : ?ShoppingCart;

    public function findProductsByCart(string $cartId) : \Illuminate\Database\Eloquent\Collection;

    public function countUserCarts(UserInterface $user) : int;

    public function findByMarketUser(UserInterface $user, string $market = 'default') : ?ShoppingCart;
} 