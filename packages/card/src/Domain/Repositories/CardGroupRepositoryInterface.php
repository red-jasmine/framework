<?php

namespace RedJasmine\Card\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 卡密分组仓库接口
 *
 * 提供卡密分组实体的读写操作统一接口
 */
interface CardGroupRepositoryInterface extends RepositoryInterface
{
    // 合并了原CardGroupReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
