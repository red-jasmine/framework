<?php

namespace RedJasmine\Logistics\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 物流运费模板仓库接口
 *
 * 提供物流运费模板实体的读写操作统一接口
 */
interface LogisticsFreightTemplateRepositoryInterface extends RepositoryInterface
{
    // 合并了原LogisticsFreightTemplateReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
