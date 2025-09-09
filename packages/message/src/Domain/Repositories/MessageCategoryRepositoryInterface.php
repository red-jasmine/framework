<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息分类仓库接口
 *
 * 提供消息分类实体的读写操作统一接口
 */
interface MessageCategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * 获取分类树
     * 合并了原MessageCategoryReadRepositoryInterface中的方法
     */
    public function tree(?Query $query = null): array;
}
