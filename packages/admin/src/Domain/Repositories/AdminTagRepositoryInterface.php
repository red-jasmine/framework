<?php

namespace RedJasmine\Admin\Domain\Repositories;

use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;

/**
 * 管理员标签仓库接口
 *
 * 提供管理员标签数据的读写操作统一接口
 */
interface AdminTagRepositoryInterface extends UserTagRepositoryInterface
{
    // 继承基础的用户标签仓库方法
    // 可根据需要扩展管理员标签特定的业务方法
}