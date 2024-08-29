<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use RedJasmine\Support\Data\Data;

/**
 * 分页查询类，用于处理数据的分页逻辑
 * 继承自 Data 类，提供分页、字段、排序等查询参数的管理
 */
class PaginateQuery extends Data
{
    /**
     * 当前页码
     * @var int|null
     */
    public ?int $page = null;

    /**
     * 每页显示数量
     * @var int|null
     */
    public ?int $perPage = null;

    /**
     * 关联加载的字段，用于指定需要关联加载的数据
     * @var mixed
     */
    public mixed $include;

    /**
     * 需要返回的字段，用于指定查询结果中包含的字段
     * @var mixed
     */
    public mixed $fields;

    /**
     * 附加字段，用于指定查询结果中需要附加的额外字段
     * @var mixed
     */
    public mixed $append;

    /**
     * 排序字段，用于指定查询结果的排序依据
     * @var mixed
     */
    public mixed $sort;

    /**
     * 是否使用简单分页模式
     * 简单分页模式仅返回当前页的数据，而不获取所有数据进行分页
     * @var bool
     */
    public bool $simplePaginate = false;
}
