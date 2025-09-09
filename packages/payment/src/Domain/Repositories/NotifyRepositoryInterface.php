<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Notify;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 支付通知仓库接口
 *
 * 提供支付通知实体的读写操作统一接口
 *
 * @method Notify  find($id)
 */
interface NotifyRepositoryInterface extends RepositoryInterface
{
    public function findByNo(string $no) : ?Notify;

    // 合并了原NotifyReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
