<?php

namespace RedJasmine\Order\Application\Services\Orders\Queries;

class FindQuery extends \RedJasmine\Support\Domain\Data\Queries\FindQuery
{
    protected string $primaryKey = 'orderNo';

}