<?php

namespace RedJasmine\Region\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 地区仓库接口
 *
 * 提供地区实体的读写操作统一接口
 */
interface RegionRepositoryInterface extends RepositoryInterface
{
    /**
     * 获取树形结构数据
     */
    public function tree(?Query $query = null): array;

    /**
     * 获取子级数据
     */
    public function children(?Query $query): array;
}
