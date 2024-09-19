<?php

namespace RedJasmine\Card\Application\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class CardGroupPaginateQuery extends PaginateQuery
{
    public ?string $name;


}
