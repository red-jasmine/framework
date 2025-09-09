<?php

namespace RedJasmine\Card\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 卡密仓库接口
 *
 * 提供卡密实体的读写操作统一接口
 */
interface CardRepositoryInterface extends RepositoryInterface
{
    // 合并了原CardReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
