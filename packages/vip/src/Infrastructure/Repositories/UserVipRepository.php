<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;

class UserVipRepository extends EloquentRepository implements VipRepositoryInterface
{

    protected static string $eloquentModelClass = UserVip::class;

}