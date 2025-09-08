<?php

namespace RedJasmine\Shop\Domain\Repositories;

use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;

/**
 * 店铺分组仓库接口
 *
 * 提供店铺分组数据的读写操作统一接口
 */
interface ShopGroupRepositoryInterface extends UserGroupRepositoryInterface
{
    // 继承基础的用户分组仓库方法
    // 可根据需要扩展店铺分组特定的业务方法
} 