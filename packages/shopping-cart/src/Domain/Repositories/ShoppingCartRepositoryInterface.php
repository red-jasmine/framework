<?php

namespace RedJasmine\ShoppingCart\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method ShoppingCart find($id)
 */
interface ShoppingCartRepositoryInterface extends RepositoryInterface
{
    public function findByUser(UserInterface $user, string $market) : ?ShoppingCart;

    public function findActiveByUser(UserInterface $user, string $market) : ?ShoppingCart;

    public function findExpiredCarts() : Collection;

    public function clearExpiredCarts() : int;
} 