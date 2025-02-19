<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;

class UserVipRepository extends EloquentRepository implements UserVipRepositoryInterface
{

    protected static string $eloquentModelClass = UserVip::class;

}