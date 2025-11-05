<?php

namespace RedJasmine\Region\Application\Services\Region\Queries;

use RedJasmine\Support\Domain\Data\Queries\Query;

class RegionTreeQuery extends Query
{
    /**
     * 国家代码 ISO 3166-1 alpha-2
     */
    public string $countryCode = 'CN';

    /**
     * 树层级
     */
    public int $level = 3;

    /**
     * 类型过滤
     */
    public ?array $type = [];
}