<?php

namespace RedJasmine\Warehouse\Application\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class WarehousePaginateQuery extends PaginateQuery
{
    /**
     * 仓库编码（搜索）
     */
    public ?string $code = null;

    /**
     * 仓库名称（搜索）
     */
    public ?string $name = null;

    /**
     * 仓库类型
     */
    public ?string $warehouseType = null;

    /**
     * 是否启用
     */
    public ?bool $isActive = null;

    /**
     * 是否默认仓库
     */
    public ?bool $isDefault = null;

    /**
     * 所属者类型
     */
    public ?string $ownerType = null;

    /**
     * 所属者ID
     */
    public ?string $ownerId = null;
}

