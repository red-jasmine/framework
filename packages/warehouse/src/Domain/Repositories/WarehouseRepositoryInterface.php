<?php

namespace RedJasmine\Warehouse\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Warehouse\Domain\Models\Warehouse;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 仓库仓库接口
 *
 * 提供仓库实体的读写操作统一接口
 *
 * @method Warehouse find($id)
 */
interface WarehouseRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据编码查找仓库
     *
     * @param string $code 仓库编码
     * @return Warehouse|null
     */
    public function findByCode(string $code): ?Warehouse;

    /**
     * 获取默认仓库
     *
     * @return Warehouse|null
     */
    public function getDefaultWarehouse(): ?Warehouse;

    /**
     * 根据市场/门店查找仓库列表
     *
     * @param string $market 市场
     * @param string $store 门店（默认为'*'表示所有门店）
     * @return Collection<Warehouse>
     */
    public function findByMarketAndStore(string $market, string $store = '*'): Collection;

    /**
     * 根据类型查找仓库列表
     *
     * @param string $type 仓库类型
     * @return Collection<Warehouse>
     */
    public function findByType(string $type): Collection;

    /**
     * 获取所有启用的仓库
     *
     * @return Collection<Warehouse>
     */
    public function getActiveWarehouses(): Collection;
}

