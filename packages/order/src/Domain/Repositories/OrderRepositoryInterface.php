<?php

namespace RedJasmine\Order\Domain\Repositories;


use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Order find($id)
 */
interface OrderRepositoryInterface extends RepositoryInterface
{


    public function findByNo(string $no) : Order;

}
