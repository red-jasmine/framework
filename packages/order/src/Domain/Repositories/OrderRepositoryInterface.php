<?php

namespace RedJasmine\Order\Domain\Repositories;


use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 订单仓库接口
 *
 * 提供订单实体的读写操作统一接口
 *
 * @method Order find($id)
 */
interface OrderRepositoryInterface extends RepositoryInterface
{
    public function findByNo(string $no) : Order;

    // 合并了原OrderReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
