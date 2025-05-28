<?php

namespace RedJasmine\Order\Domain\Repositories;

use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;


interface RefundReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findByNo(string $no) : Refund;
}
