<?php

namespace RedJasmine\Order\Domain\Repositories;


use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 订单支付仓库接口
 *
 * 提供订单支付实体的读写操作统一接口
 *
 * @method OrderPayment find($id)
 */
interface OrderPaymentRepositoryInterface extends RepositoryInterface
{
    // 合并了原OrderPaymentReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
