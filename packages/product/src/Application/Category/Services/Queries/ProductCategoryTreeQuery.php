<?php

namespace RedJasmine\Product\Application\Category\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\Query;

class ProductCategoryTreeQuery extends Query
{

    public ?string $status;


    public mixed $append;

    public mixed $sort;


}
