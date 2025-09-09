<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Support\Domain\Repositories\BaseRepositoryInterface;

/**
 * 推广员申请仓库接口
 *
 * 提供推广员申请实体的读写操作统一接口
 *
 * @method PromoterApply find($id)
 */
interface PromoterApplyRepositoryInterface extends BaseRepositoryInterface
{
    // 合并了原PromoterApplyReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
