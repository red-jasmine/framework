<?php

namespace RedJasmine\User\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\User\Domain\Models\UserGroup;
use RedJasmine\User\Domain\Repositories\UserGroupReadRepositoryInterface;

class UserGroupReadRepository extends QueryBuilderReadRepository implements UserGroupReadRepositoryInterface
{
    public static string $modelClass = UserGroup::class;

    use HasTree;
}