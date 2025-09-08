<?php

namespace RedJasmine\Announcement\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找分类
     */
    public function findByName($name) : ?AnnouncementCategory;

    /**
     * 获取分类树
     */
    public function tree(?Query $query = null) : array;

    /**
     * 根据查询条件查找单个分类
     */
    public function findByQuery(FindQuery $query) : ?AnnouncementCategory;

    /**
     * 分页查询分类
     */
    public function paginate(PaginateQuery $query) : LengthAwarePaginator;

    /**
     * 设置查询作用域
     */
    public function withQuery(\Closure $closure) : static;
}
