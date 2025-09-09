<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 支付商户仓库接口
 *
 * 提供支付商户实体的读写操作统一接口
 *
 * @method Merchant  find($id)
 */
interface MerchantRepositoryInterface extends RepositoryInterface
{
    // 合并了原MerchantReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
