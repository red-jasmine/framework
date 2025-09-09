<?php

namespace RedJasmine\Order\Domain\Repositories;

use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 退款仓库接口
 *
 * 提供退款实体的读写操作统一接口
 *
 * @method Refund find($id)
 */
interface RefundRepositoryInterface extends RepositoryInterface
{
    public function findByNo(string $no) : Refund;

    // 合并了原RefundReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
