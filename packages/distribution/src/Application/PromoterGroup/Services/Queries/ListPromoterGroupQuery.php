<?php

namespace RedJasmine\Distribution\Application\PromoterGroup\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\Query;

class ListPromoterGroupQuery extends Query
{
    public function __construct(
        public array $filters = []
    ) {}
}
