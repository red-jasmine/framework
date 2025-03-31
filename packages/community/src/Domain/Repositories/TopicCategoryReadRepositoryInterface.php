<?php

namespace RedJasmine\Comnunity\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface TopicCategoryReadRepositoryInterface extends ReadRepositoryInterface
{

    public function tree(Query $query) : array;

}