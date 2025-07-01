<?php

namespace RedJasmine\ShoppingCart\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartReadRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ShoppingCartReadRepository extends QueryBuilderReadRepository implements ShoppingCartReadRepositoryInterface
{
    public static $modelClass = ShoppingCart::class;

    public function findWithProducts(string $cartId) : ?ShoppingCart
    {
        return ShoppingCart::with('products')->find($cartId);
    }

    public function findProductsByCart(string $cartId) : Collection
    {
        return ShoppingCartProduct::query()->where('cart_id', $cartId)->get();
    }

    public function countUserCarts(UserInterface $user) : int
    {
        return ShoppingCart::query()
                           ->where('owner_type', $user->getType())
                           ->where('owner_id', $user->getID())
                           ->count();
    }

    public function allowedFilters() : array
    {
        return [];
    }

    public function allowedSorts() : array
    {
        return [];
    }

    public function allowedIncludes() : array
    {
        return ['products'];
    }

    public function findByMarketUser(UserInterface $user, string $market = 'default') : ?ShoppingCart
    {
        return $this->query()
                    ->where('owner_type', $user->getType())
                    ->where('owner_id', $user->getID())
                    ->where('market', $market)
                    ->where('status', 'active')
                    ->first();
    }
}