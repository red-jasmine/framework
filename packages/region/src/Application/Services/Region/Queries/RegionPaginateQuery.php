<?php

namespace RedJasmine\Region\Application\Services\Region\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class RegionPaginateQuery extends PaginateQuery
{
    /**
     * 代码
     */
    public ?string $code = null;

    /**
     * 国家代码 ISO 3166-1 alpha-2
     */
    public ?string $countryCode = null;

    /**
     * 父级编码
     */
    public ?string $parentCode = null;

    /**
     * 名称
     */
    public ?string $name = null;
}