<?php

namespace RedJasmine\Shop\Infrastructure\Repositories;

use RedJasmine\Shop\Domain\Models\ShopGroup;
use RedJasmine\Shop\Domain\Repositories\ShopGroupRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserGroupRepository;

/**
 * 店铺分组仓库实现
 *
 * 提供店铺分组数据的读写操作统一实现
 */
class ShopGroupRepository extends UserGroupRepository implements ShopGroupRepositoryInterface
{
    protected static string $modelClass = ShopGroup::class;
} 