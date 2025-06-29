<?php

namespace RedJasmine\Shop\Infrastructure\Repositories;

use RedJasmine\Shop\Domain\Models\ShopTag;
use RedJasmine\Shop\Domain\Repositories\ShopTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ShopTagRepository extends EloquentRepository implements ShopTagRepositoryInterface
{
    protected static string $eloquentModelClass = ShopTag::class;
} 