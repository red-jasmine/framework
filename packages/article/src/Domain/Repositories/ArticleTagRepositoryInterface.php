<?php

namespace RedJasmine\Article\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 文章标签仓库接口
 *
 * 提供文章标签实体的读写操作统一接口
 */
interface ArticleTagRepositoryInterface extends RepositoryInterface
{
    // 合并了原ArticleTagReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
