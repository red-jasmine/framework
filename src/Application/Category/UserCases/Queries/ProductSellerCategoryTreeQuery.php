<?php

namespace RedJasmine\Product\Application\Category\UserCases\Queries;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Data\Queries\Query;

class ProductSellerCategoryTreeQuery extends Query
{

    public ?string $ownerType = null;

    public ?int $ownerId = null;

    public ?string $status;
    public ?bool   $isShow;


    public string|array|null $append;

    public string|array|null $sort;


}
