<?php

namespace RedJasmine\Shop\Infrastructure\Repositories;

use RedJasmine\Shop\Domain\Models\ShopTag;
use RedJasmine\Shop\Domain\Repositories\ShopTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ShopTagRepository extends Repository implements ShopTagRepositoryInterface
{
    protected static string $modelClass = ShopTag::class;
} 