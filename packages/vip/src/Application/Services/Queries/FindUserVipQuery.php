<?php

namespace RedJasmine\Vip\Application\Services\Queries;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;

class FindUserVipQuery extends Query
{

    public UserInterface $owner;

    public string $biz;

    public string $type;


}