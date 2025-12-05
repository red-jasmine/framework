<?php

namespace RedJasmine\ShoppingCart\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 购物车仓库接口
 *
 * 提供购物车实体的读写操作统一接口
 *
 * @method ShoppingCart find($id)
 */
interface ShoppingCartRepositoryInterface extends RepositoryInterface
{
    // 写操作方法
    public function findActiveByUser(UserInterface $user, string $market): ?ShoppingCart;

    public function findExpiredCarts(): Collection;

    public function clearExpiredCarts(): int;

    public function deleteProduct(ShoppingCartProduct $product): bool;

    // 合并的读操作方法
    public function findWithProducts(string $cartId): ?ShoppingCart;

    public function findProductsByCart(string $cartId): Collection;

    public function countUserCarts(UserInterface $user): int;

    public function findByMarketUser(UserInterface $user, string $market = 'default'): ?ShoppingCart;
}