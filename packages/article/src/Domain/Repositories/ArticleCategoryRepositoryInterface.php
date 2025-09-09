<?php

namespace RedJasmine\Article\Domain\Repositories;

use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 文章分类仓库接口
 *
 * 提供文章分类实体的读写操作统一接口
 */
interface ArticleCategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找分类
     */
    public function findByName($name) : ?ArticleCategory;

    /**
     * 获取树形结构
     * 合并了原ArticleCategoryReadRepositoryInterface中tree方法
     */
    public function tree(Query $query) : array;
}
