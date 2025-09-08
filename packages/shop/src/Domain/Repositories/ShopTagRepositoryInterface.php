<?php

namespace RedJasmine\Shop\Domain\Repositories;

use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;

/**
 * 店铺标签仓库接口
 *
 * 提供店铺标签数据的读写操作统一接口
 */
interface ShopTagRepositoryInterface extends UserTagRepositoryInterface
{
    // 继承基础的用户标签仓库方法
    // 可根据需要扩展店铺标签特定的业务方法
} 