<?php

namespace RedJasmine\Community\Domain\Repositories;

use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 话题分类仓库接口
 *
 * 提供话题分类实体的读写操作统一接口
 */
interface TopicCategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找分类
     */
    public function findByName($name) : ?TopicCategory;

    /**
     * 获取树形结构
     *
     */
    public function tree(Query $query) : array;


}
