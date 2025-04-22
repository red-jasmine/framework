<?php

namespace RedJasmine\Address\Application\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class AddressPaginateQuery extends PaginateQuery
{

    public ?string $type;

}