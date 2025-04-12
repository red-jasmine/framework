<?php

namespace RedJasmine\Wallet\Application\Services\Wallet\Queries;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;

class FindByOwnerTypeQuery extends Query
{

    public UserInterface $owner;

    public string $type;

}