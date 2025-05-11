<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\User\Domain\Models\UserTagCategory;
use RedJasmine\User\Domain\Repositories\UserTagCategoryRepositoryInterface;

class UserTagCategoryRepository extends EloquentRepository implements UserTagCategoryRepositoryInterface
{
    protected static string $eloquentModelClass = UserTagCategory::class;
}