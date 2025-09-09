<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 支付渠道仓库接口
 *
 * 提供支付渠道实体的读写操作统一接口
 *
 * @method Channel  find($id)
 */
interface ChannelRepositoryInterface extends RepositoryInterface
{
    public function findByCode(string $code) : ?Channel;

    // 合并了原ChannelReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
