<?php

namespace RedJasmine\Admin\Infrastructure\Repositories;

use RedJasmine\Admin\Domain\Models\AdminTag;
use RedJasmine\Admin\Domain\Repositories\AdminGroupRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserGroupRepository;

class AdminGroupRepository extends UserGroupRepository implements AdminGroupRepositoryInterface
{

    protected static string $modelClass = AdminTag::class;
}