<?php

namespace RedJasmine\Admin\Infrastructure\Repositories;

use RedJasmine\Admin\Domain\Models\AdminTag;
use RedJasmine\Admin\Domain\Repositories\AdminTagRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserTagRepository;

class AdminTagRepository extends UserTagRepository implements AdminTagRepositoryInterface
{
    protected static string $modelClass = AdminTag::class;
}