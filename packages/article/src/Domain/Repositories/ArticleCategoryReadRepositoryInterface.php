<?php

namespace RedJasmine\Article\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ArticleCategoryReadRepositoryInterface extends ReadRepositoryInterface
{

    public function tree(Query $query) : array;

}