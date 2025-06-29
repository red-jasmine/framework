<?php

namespace RedJasmine\Shop\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Shop\Domain\Models\ShopTag;
use RedJasmine\Shop\Domain\Repositories\ShopTagReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ShopTagReadRepository extends QueryBuilderReadRepository implements ShopTagReadRepositoryInterface
{
    public static string $modelClass = ShopTag::class;
} 