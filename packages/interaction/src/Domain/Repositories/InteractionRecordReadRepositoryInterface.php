<?php

namespace RedJasmine\Interaction\Domain\Repositories;


use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface InteractionRecordReadRepositoryInterface extends ReadRepositoryInterface
{


    public function findByResourceUserLast(FindQuery $query);


}