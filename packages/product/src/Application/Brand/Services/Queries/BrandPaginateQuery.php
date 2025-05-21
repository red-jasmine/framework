<?php

namespace RedJasmine\Product\Application\Brand\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class BrandPaginateQuery extends PaginateQuery
{

    public ?int $id;

    public ?int $parentId;

    public ?string $name;

    public ?string $initial;



    /**
     * 搜索
     * @var string|null
     */
    public ?string $search;

}
