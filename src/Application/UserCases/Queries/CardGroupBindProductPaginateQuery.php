<?php

namespace RedJasmine\Card\Application\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class CardGroupBindProductPaginateQuery extends PaginateQuery
{
    public ?string $productType;
    public ?int    $productId;
    public ?int    $groupId;
    public ?int    $skuId;


}
