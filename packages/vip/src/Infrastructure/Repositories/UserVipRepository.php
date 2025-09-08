<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;

class UserVipRepository extends Repository implements UserVipRepositoryInterface
{

    protected static string $modelClass = UserVip::class;

}