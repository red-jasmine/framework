<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use RedJasmine\Support\Domain\Data\Queries\Query;

trait HasTree
{

    public function tree(?Query $query = null) : array
    {
        $nodes = $this->queryBuilder($query)->get();

        $model = (new static::$modelClass);

        return $model->toTree($nodes);
    }



}
