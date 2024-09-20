<?php

namespace RedJasmine\Product\Application\Category\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\Query;

class ProductCategoryTreeQuery extends Query
{

    public ?string $status;


    public string|array|null $append;

    public string|array|null $sort;


}
