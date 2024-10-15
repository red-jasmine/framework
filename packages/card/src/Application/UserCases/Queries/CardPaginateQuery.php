<?php

namespace RedJasmine\Card\Application\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class CardPaginateQuery extends PaginateQuery
{


    public ?string $status;
    public ?bool   $isLoop;

}
