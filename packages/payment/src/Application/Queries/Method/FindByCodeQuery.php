<?php

namespace RedJasmine\Payment\Application\Queries\Method;

use RedJasmine\Support\Domain\Data\Queries\Query;

class FindByCodeQuery extends Query
{
    public string $code;
}
