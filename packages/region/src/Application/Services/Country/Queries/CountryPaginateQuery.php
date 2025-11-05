<?php

namespace RedJasmine\Region\Application\Services\Country\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class CountryPaginateQuery extends PaginateQuery
{
    /**
     * 国家代码 ISO 3166-1 alpha-2
     */
    public ?string $code = null;

    /**
     * 国家代码 ISO 3166-1 alpha-3
     */
    public ?string $isoAlpha3 = null;

    /**
     * 名称
     */
    public ?string $name = null;

    /**
     * 大区
     */
    public ?string $region = null;

    /**
     * 货币代码
     */
    public ?string $currency = null;

    /**
     * 电话区号
     */
    public ?string $phoneCode = null;
}
