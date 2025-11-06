<?php

namespace RedJasmine\Region\Application\Services\Region\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class RegionChildrenQuery extends PaginateQuery
{
    /**
     * 国家代码 ISO 3166-1 alpha-2
     */
    public ?string $countryCode = null;

    /**
     * 父级编码
     */
    public ?string $parentCode = null;

    /**
     * 地区类型
     */
    public ?string $type = null;

    public ?int $perPage = 10000;
}
