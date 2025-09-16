<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterTeam;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 推广员团队仓库接口
 *
 * 提供推广员团队实体的读写操作统一接口
 *
 * @method PromoterTeam find($id)
 */
interface PromoterTeamRepositoryInterface extends RepositoryInterface
{
    // 合并了原PromoterTeamReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
