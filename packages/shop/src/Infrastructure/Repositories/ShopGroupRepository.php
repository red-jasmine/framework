<?php

namespace RedJasmine\Shop\Infrastructure\Repositories;

use RedJasmine\Shop\Domain\Models\ShopGroup;
use RedJasmine\Shop\Domain\Repositories\ShopGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ShopGroupRepository extends EloquentRepository implements ShopGroupRepositoryInterface
{
    protected static string $eloquentModelClass = ShopGroup::class;
} 