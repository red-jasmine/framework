<?php

namespace RedJasmine\Admin\Infrastructure\Repositories;

use RedJasmine\Admin\Domain\Models\AdminGroup;
use RedJasmine\Admin\Domain\Repositories\AdminGroupRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserGroupRepository;

/**
 * 管理员分组仓库实现
 *
 * 提供管理员分组数据的读写操作统一实现
 */
class AdminGroupRepository extends UserGroupRepository implements AdminGroupRepositoryInterface
{
    protected static string $modelClass = AdminGroup::class;
}