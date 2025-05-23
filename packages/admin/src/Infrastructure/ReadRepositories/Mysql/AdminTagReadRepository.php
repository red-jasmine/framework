<?php

namespace RedJasmine\Admin\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Admin\Domain\Models\AdminTag;
use RedJasmine\Admin\Domain\Repositories\AdminTagReadRepositoryInterface;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserTagReadRepository;

class AdminTagReadRepository extends UserTagReadRepository implements AdminTagReadRepositoryInterface
{
    public static string $modelClass = AdminTag::class;
}