<?php

namespace RedJasmine\ShoppingCart\Domain\Repositories;

use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method ShoppingCart find($id)
 */
interface ShoppingCartRepositoryInterface extends RepositoryInterface
{
    public function findByUser(UserInterface $user): ?ShoppingCart;
    public function findActiveByUser(UserInterface $user): ?ShoppingCart;
    public function findExpiredCarts(): \Illuminate\Database\Eloquent\Collection;
    public function clearExpiredCarts(): int;
} 