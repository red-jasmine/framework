<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\User\Domain\Models\UserTag;
use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;

class UserTagRepository extends Repository implements UserTagRepositoryInterface
{
    protected static string $modelClass = UserTag::class;

    public function tree(Query $query) : array
    {
        $nodes = $this->queryBuilder($query)->get();
        $model = new static::$modelClass;
        return $model->toTree($nodes);
    }
}