<?php

namespace RedJasmine\Support\Domain\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

/**
 * 读取仓库接口，定义了数据读取的相关规范
 */
interface ReadRepositoryInterface
{

    /**
     * 根据ID查找实体
     *
     * @param  mixed  $id  实体的ID
     * @param  FindQuery|null  $findQuery  可选的查找查询对象，用于定制查找条件
     *
     * @return mixed 查找到的实体数据
     */
    public function find($id, FindQuery $findQuery = null);

    /**
     * 分页查询实体列表
     *
     * @param  PaginateQuery|null  $findQuery  可选的分页查询对象，包含分页及排序等信息
     *
     * @return LengthAwarePaginator 分页后的实体列表，包含总记录数信息
     */
    public function paginate(?PaginateQuery $findQuery = null) : LengthAwarePaginator;

    /**
     * 简单分页查询实体列表
     *
     * 与paginate方法的区别在于，simplePaginate只提供基本的分页功能，不包括总记录数
     *
     * @param  PaginateQuery|null  $findQuery  可选的分页查询对象，包含分页信息
     *
     * @return Paginator 分页后的实体列表，不包含总记录数信息
     */
    public function simplePaginate(?PaginateQuery $findQuery = null) : Paginator;

}
