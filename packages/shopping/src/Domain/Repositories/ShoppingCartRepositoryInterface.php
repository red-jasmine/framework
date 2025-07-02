<?php

namespace RedJasmine\Shopping\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method ShoppingCart find($id)
 */
interface ShoppingCartRepositoryInterface extends RepositoryInterface
{

    public function findActiveByUser(UserInterface $user, string $market) : ?ShoppingCart;

    public function findExpiredCarts() : Collection;

    public function clearExpiredCarts() : int;
} 