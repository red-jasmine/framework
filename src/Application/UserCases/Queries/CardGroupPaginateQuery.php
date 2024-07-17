<?php

namespace RedJasmine\Card\Application\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class CardGroupPaginateQuery extends PaginateQuery
{
    public ?string $name;


}
