<?php

namespace RedJasmine\Product\Application\Series\Services\Queries;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class SeriesPaginateQuery extends PaginateQuery
{
    public UserInterface $owner;

    public ?string       $name;

}
