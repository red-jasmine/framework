<?php

namespace RedJasmine\Community\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 话题标签仓库接口
 *
 * 提供话题标签实体的读写操作统一接口
 */
interface TopicTagRepositoryInterface extends RepositoryInterface
{
    // 合并了原TopicTagReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
