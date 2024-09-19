<?php

namespace RedJasmine\Card\Application\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class CardGroupBindProductPaginateQuery extends PaginateQuery
{
    public ?string $productType;
    public ?int    $productId;
    public ?int    $groupId;
    public ?int    $skuId;


}
