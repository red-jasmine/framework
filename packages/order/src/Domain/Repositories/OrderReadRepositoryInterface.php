<?php

namespace RedJasmine\Order\Domain\Repositories;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface OrderReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findByNo(string $no) : Order;
}
