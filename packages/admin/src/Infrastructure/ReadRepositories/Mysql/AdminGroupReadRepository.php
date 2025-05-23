<?php

namespace RedJasmine\Admin\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Admin\Domain\Models\AdminGroup;
use RedJasmine\Admin\Domain\Repositories\AdminGroupReadRepositoryInterface;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserGroupReadRepository;

class AdminGroupReadRepository extends UserGroupReadRepository implements AdminGroupReadRepositoryInterface
{
    public static string $modelClass = AdminGroup::class;
}