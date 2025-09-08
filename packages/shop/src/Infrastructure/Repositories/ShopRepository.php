<?php

namespace RedJasmine\Shop\Infrastructure\Repositories;

use RedJasmine\Shop\Domain\Models\Shop;
use RedJasmine\Shop\Domain\Repositories\ShopRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserRepository;

class ShopRepository extends UserRepository implements ShopRepositoryInterface
{
    protected static string $modelClass = Shop::class;

    public function findByName(string $name): ?Shop
    {
        return parent::findByName($name);
    }
} 