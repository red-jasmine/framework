<?php

namespace RedJasmine\Support\Domain\Repositories;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * BaseRepositoryInterface 基础仓库接口
 *
 * 定义了仓储模式的基础契约，包含读取和写入操作的统一接口。
 * 该接口遵循 DDD 架构原则，提供了数据访问层的抽象，使得具体的数据操作实现
 * 可以适配不同的数据存储机制，同时为上层业务逻辑提供统一的操作接口。
 *
 * 功能特性：
 * - 写操作：增删改查的基本CRUD操作
 * - 读操作：查询构建、分页查询、条件过滤
 */
interface BaseRepositoryInterface
{
    // ========================================
    // 写操作接口 (Write Operations)
    // ========================================

    /**
     * 根据ID查找记录
     *
     * @param  mixed  $id  要查找的记录ID
     *
     * @return Model|null 找到的模型实例，未找到时返回null
     */
    public function find(mixed $id) : ?Model;


    /**
     * 根据ID查找记录并加锁
     *
     * @param  mixed  $id  要查找的记录ID
     *
     * @return Model|null 找到的模型实例，未找到时返回null
     */
    public function findLock(mixed $id) : ?Model;

    /**
     * 存储模型实例到数据库
     *
     * @param  Model  $model  要存储的模型实例
     *
     * @return Model 存储后的模型实例
     */
    public function store(Model $model) : Model;

    /**
     * 更新模型实例数据
     *
     * @param  Model  $model  要更新的模型实例
     *
     * @return Model 更新后的模型实例
     */
    public function update(Model $model) : Model;

    /**
     * 从数据库删除模型实例
     *
     * @param  Model  $model  要删除的模型实例
     *
     * @return bool 删除操作是否成功
     */
    public function delete(Model $model) : bool;

    // ========================================
    // 读操作接口 (Read Operations)
    // ========================================

    /**
     * 获取模型查询构建器
     *
     * @return Builder
     */
    function query() : Builder;


    /**
     * 添加自定义查询回调函数
     *
     * @param  Closure  $queryCallback  查询回调函数，用于自定义查询逻辑
     *
     * @return static 返回当前仓库实例以支持链式调用
     */
    public function withQuery(Closure $queryCallback) : static;


    /**
     * 根据查询条件查找单个记录
     *
     * @param  FindQuery  $query  查找查询对象，包含查找条件
     *
     * @return Model|null 找到的模型实例，未找到时返回null
     */
    public function findByQuery(FindQuery $query) : ?Model;


    /**
     * 分页查询记录列表
     *
     * @param  PaginateQuery  $query  分页查询对象，包含分页及排序等信息
     *
     * @return LengthAwarePaginator|Paginator 分页后的记录列表
     */
    public function paginate(PaginateQuery $query) : LengthAwarePaginator|Paginator;

}
