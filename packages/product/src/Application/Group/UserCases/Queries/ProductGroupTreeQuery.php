<?php

namespace RedJasmine\Product\Application\Group\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\Query;

class ProductGroupTreeQuery extends Query
{

    public ?string $ownerType = null;

    public ?int $ownerId = null;

    public ?string $status;
    public ?bool   $isShow;


    public string|array|null $append;

    public string|array|null $sort;


}
