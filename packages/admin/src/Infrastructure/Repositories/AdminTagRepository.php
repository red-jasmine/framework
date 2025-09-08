<?php

namespace RedJasmine\Admin\Infrastructure\Repositories;

use RedJasmine\Admin\Domain\Models\AdminTag;
use RedJasmine\Admin\Domain\Repositories\AdminTagRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserTagRepository;

/**
 * 管理员标签仓库实现
 *
 * 提供管理员标签数据的读写操作统一实现
 */
class AdminTagRepository extends UserTagRepository implements AdminTagRepositoryInterface
{
    protected static string $modelClass = AdminTag::class;
}