<?php

namespace RedJasmine\Shop\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Shop\Domain\Models\ShopGroup;
use RedJasmine\Shop\Domain\Repositories\ShopGroupReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ShopGroupReadRepository extends QueryBuilderReadRepository implements ShopGroupReadRepositoryInterface
{
    public static string $modelClass = ShopGroup::class;
} 