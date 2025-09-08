<?php

namespace RedJasmine\Shop\Infrastructure\Repositories;

use RedJasmine\Shop\Domain\Models\ShopGroup;
use RedJasmine\Shop\Domain\Repositories\ShopGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ShopGroupRepository extends Repository implements ShopGroupRepositoryInterface
{
    protected static string $modelClass = ShopGroup::class;
} 