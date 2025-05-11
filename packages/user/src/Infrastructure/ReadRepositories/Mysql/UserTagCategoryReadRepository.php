<?php

namespace RedJasmine\User\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\User\Domain\Models\UserTagCategory;
use RedJasmine\User\Domain\Repositories\UserTagCategoryReadRepositoryInterface;

class UserTagCategoryReadRepository extends QueryBuilderReadRepository implements UserTagCategoryReadRepositoryInterface
{
    public static string $modelClass = UserTagCategory::class;

    use HasTree;
}