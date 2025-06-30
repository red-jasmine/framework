<?php

namespace RedJasmine\ShoppingCart\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ShoppingCartRepository extends EloquentRepository implements ShoppingCartRepositoryInterface
{
    protected static string $eloquentModelClass = ShoppingCart::class;

    public function findByUser(UserInterface $user): ?ShoppingCart
    {
        return ShoppingCart::query()
            ->where('owner_type', $user->getType())
            ->where('owner_id', $user->getID())
            ->first();
    }

    public function findActiveByUser(UserInterface $user): ?ShoppingCart
    {
        return ShoppingCart::query()
            ->where('owner_type', $user->getType())
            ->where('owner_id', $user->getID())
            ->where('status', 'active')
            ->first();
    }

    public function findExpiredCarts(): Collection
    {
        return ShoppingCart::query()->where('status', 'expired')->get();
    }

    public function clearExpiredCarts(): int
    {
        return ShoppingCart::query()->where('status', 'expired')->delete();
    }
} 