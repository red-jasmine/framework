<?php

namespace RedJasmine\User\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\User\Domain\Models\UserTag;
use RedJasmine\User\Domain\Repositories\UserTagReadRepositoryInterface;

class UserTagReadRepository extends QueryBuilderReadRepository implements UserTagReadRepositoryInterface
{
    public static string $modelClass = UserTag::class;

    use HasTree;
}