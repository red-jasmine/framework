<?php

namespace RedJasmine\Product\Application\Group\Services\Queries;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;

class ProductGroupTreeQuery extends Query
{

    public UserInterface $owner;

    public ?string $status;

    public ?bool   $isShow;


}
