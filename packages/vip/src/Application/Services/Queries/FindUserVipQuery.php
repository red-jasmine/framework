<?php

namespace RedJasmine\Vip\Application\Services\Queries;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;

class FindUserVipQuery extends Query
{

    public UserInterface $owner;

    public string $appId;

    public string $type;


}