<?php

namespace RedJasmine\Region\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 国家仓库接口
 *
 * 提供国家实体的读写操作统一接口
 */
interface CountryRepositoryInterface extends RepositoryInterface
{
    // 合并了原CountryReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
