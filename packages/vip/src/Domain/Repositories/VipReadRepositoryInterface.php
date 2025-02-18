<?php

namespace RedJasmine\Vip\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Vip\Domain\Models\Vip;

interface VipReadRepositoryInterface extends ReadRepositoryInterface
{


    public function findVipType(string $appID, string $type) : ?Vip;

}