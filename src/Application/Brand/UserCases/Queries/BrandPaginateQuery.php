<?php

namespace RedJasmine\Product\Application\Brand\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class BrandPaginateQuery extends PaginateQuery
{

    public ?int $id;

    public ?int $parentId;

    public ?string $name;

    public ?string $english_name;

    public ?string $initial;


    public ?string $englishName;


    /**
     * 搜索
     * @var string|null
     */
    public ?string $search;

}
