<?php

namespace RedJasmine\Card\Application\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class CardPaginateQuery extends PaginateQuery
{


    public ?string $status;
    public ?string $productType;
    public ?int    $productId;
    public ?int    $skuId;
    public ?bool   $isLoop;

}
