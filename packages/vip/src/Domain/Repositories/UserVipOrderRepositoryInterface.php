<?php

namespace RedJasmine\Vip\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface UserVipOrderRepositoryInterface extends RepositoryInterface
{

    public function stores(Collection $orders) : bool;
}