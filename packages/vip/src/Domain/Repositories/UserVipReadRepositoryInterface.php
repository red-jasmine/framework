<?php

namespace RedJasmine\Vip\Domain\Repositories;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Vip\Domain\Models\UserVip;

interface UserVipReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findVipByOwner(UserInterface $owner, string $biz, string $type) : ?UserVip;

}