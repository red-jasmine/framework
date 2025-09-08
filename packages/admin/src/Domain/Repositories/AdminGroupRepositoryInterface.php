<?php

namespace RedJasmine\Admin\Domain\Repositories;

use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;

/**
 * 管理员分组仓库接口
 *
 * 提供管理员分组数据的读写操作统一接口
 */
interface AdminGroupRepositoryInterface extends UserGroupRepositoryInterface
{
    // 继承基础的用户分组仓库方法
    // 可根据需要扩展管理员分组特定的业务方法
}