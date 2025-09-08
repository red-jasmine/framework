<?php

namespace RedJasmine\Shop\Infrastructure\Repositories;

use RedJasmine\Shop\Domain\Models\ShopTag;
use RedJasmine\Shop\Domain\Repositories\ShopTagRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserTagRepository;

/**
 * 店铺标签仓库实现
 *
 * 提供店铺标签数据的读写操作统一实现
 */
class ShopTagRepository extends UserTagRepository implements ShopTagRepositoryInterface
{
    protected static string $modelClass = ShopTag::class;
} 