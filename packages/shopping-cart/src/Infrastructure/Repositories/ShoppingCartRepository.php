<?php

namespace RedJasmine\ShoppingCart\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * 购物车仓库实现
 *
 * 基于Repository实现，提供购物车实体的读写操作能力
 */
class ShoppingCartRepository extends Repository implements ShoppingCartRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ShoppingCart::class;

    // 写操作方法
    public function findActiveByUser(UserInterface $user, string $market): ?ShoppingCart
    {
        return static::$modelClass::query()
                                  ->where('owner_type', $user->getType())
                                  ->where('owner_id', $user->getID())
                                  ->where('status', 'active')
                                  ->where('market', $market)
                                  ->first();
    }

    public function findExpiredCarts(): Collection
    {
        return static::$modelClass::query()->where('status', 'expired')->get();
    }

    public function clearExpiredCarts(): int
    {
        return static::$modelClass::query()->where('status', 'expired')->delete();
    }

    public function deleteProduct(ShoppingCartProduct $product): bool
    {
        return $product->delete();
    }

    // 合并的读操作方法
    public function findWithProducts(string $cartId): ?ShoppingCart
    {
        return ShoppingCart::with('products')->find($cartId);
    }

    public function findProductsByCart(string $cartId): Collection
    {
        return ShoppingCartProduct::query()->where('cart_id', $cartId)->get();
    }

    public function countUserCarts(UserInterface $user): int
    {
        return ShoppingCart::query()
                           ->where('owner_type', $user->getType())
                           ->where('owner_id', $user->getID())
                           ->count();
    }

    public function findByMarketUser(UserInterface $user, string $market = 'default'): ?ShoppingCart
    {
        return $this->query()
                    ->where('owner_type', $user->getType())
                    ->where('owner_id', $user->getID())
                    ->where('market', $market)
                    ->where('status', 'active')
                    ->first();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return ['products'];
    }
}
