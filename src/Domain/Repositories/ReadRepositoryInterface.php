<?php

namespace RedJasmine\Support\Domain\Repositories;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;

/**
 *
 * // 提供 模型 查询的能力,同时查询时 可携带一些参数  如 排序，获取的字段 ，分页，包含的数据
 * - find  通过id 查询
 * - paginate 分页查询
 *
 * 读取仓库接口，定义了数据读取的相关规范
 */
interface ReadRepositoryInterface
{


    /**
     * @param  Query|null  $query
     *
     * @return Builder
     */
    public function query(?Query $query = null);

    /**
     * 添加查询回调函数
     *
     * @param  Closure  $queryCallback  查询回调函数，用于自定义查询逻辑
     *
     * @return $this
     */
    public function withQuery(Closure $queryCallback) : static;

    /**
     * 根据ID查找实体
     *
     * @param  FindQuery  $query  可选的查找查询对象，用于定制查找条件
     *
     * @return Model|null 查找到的实体数据，如果未找到则返回null
     */
    public function find(FindQuery $query) : ?Model;

    /**
     * 分页查询实体列表
     *
     * @param  PaginateQuery|null  $query  可选的分页查询对象，包含分页及排序等信息
     *
     * @return LengthAwarePaginator 分页后的实体列表，包含总记录数信息
     */
    public function paginate(?PaginateQuery $query = null) : LengthAwarePaginator;

    /**
     * 简单分页查询实体列表
     *
     * 与paginate方法的区别在于，simplePaginate只提供基本的分页功能，不包括总记录数
     *
     * @param  PaginateQuery|null  $query  可选的分页查询对象，包含分页信息
     *
     * @return Paginator 分页后的实体列表，不包含总记录数信息
     */
    public function simplePaginate(?PaginateQuery $query = null) : Paginator;

}
