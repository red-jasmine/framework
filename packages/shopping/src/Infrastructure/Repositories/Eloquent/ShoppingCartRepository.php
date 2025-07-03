<?php

namespace RedJasmine\Shopping\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Shopping\Domain\Models\ShoppingCartProduct;
use RedJasmine\Shopping\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ShoppingCartRepository extends EloquentRepository implements ShoppingCartRepositoryInterface
{
    protected static string $eloquentModelClass = ShoppingCart::class;


    public function findActiveByUser(UserInterface $user, string $market) : ?ShoppingCart
    {
        return static::$eloquentModelClass::query()
                                          ->where('owner_type', $user->getType())
                                          ->where('owner_id', $user->getID())
                                          ->where('status', 'active')
                                          ->where('market', $market)
                                          ->first();
    }

    public function findExpiredCarts() : Collection
    {
        return static::$eloquentModelClass::query()->where('status', 'expired')->get();
    }

    public function clearExpiredCarts() : int
    {
        return static::$eloquentModelClass::query()->where('status', 'expired')->delete();
    }

    public function deleteProduct(ShoppingCartProduct $product) : bool
    {
        return $product->delete();
    }


} 