<?php

namespace RedJasmine\Community\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 话题仓库接口
 *
 * 提供话题实体的读写操作统一接口
 */
interface TopicRepositoryInterface extends RepositoryInterface
{
    // 合并了原TopicReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
