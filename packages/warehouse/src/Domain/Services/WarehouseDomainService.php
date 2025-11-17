<?php

namespace RedJasmine\Warehouse\Domain\Services;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Warehouse\Domain\Models\Warehouse;
use RedJasmine\Warehouse\Domain\Models\WarehouseMarket;
use RedJasmine\Warehouse\Domain\Models\Enums\WarehouseTypeEnum;
use RedJasmine\Warehouse\Domain\Repositories\WarehouseRepositoryInterface;

/**
 * 仓库领域服务
 *
 * 提供仓库相关的业务逻辑处理
 */
class WarehouseDomainService extends Service
{
    public function __construct(
        protected WarehouseRepositoryInterface $repository
    ) {
    }

    /**
     * 获取默认仓库
     *
     * @return Warehouse|null
     */
    public function getDefaultWarehouse(): ?Warehouse
    {
        return $this->repository->getDefaultWarehouse();
    }

    /**
     * 创建默认仓库（如果不存在）
     *
     * @param string $code 仓库编码
     * @param string $name 仓库名称
     * @return Warehouse
     */
    public function createDefaultWarehouse(string $code = 'DEFAULT', string $name = '默认仓库'): Warehouse
    {
        $warehouse = $this->repository->getDefaultWarehouse();
        
        if ($warehouse) {
            return $warehouse;
        }

        // 创建默认仓库
        $warehouse = new Warehouse();
        $warehouse->code = $code;
        $warehouse->name = $name;
        $warehouse->warehouse_type = WarehouseTypeEnum::WAREHOUSE;
        $warehouse->is_active = true;
        $warehouse->is_default = true;
        
        $this->repository->store($warehouse);
        
        return $warehouse;
    }

    /**
     * 根据市场/门店查找仓库列表
     *
     * @param string $market 市场
     * @param string $store 门店（默认为'*'表示所有门店）
     * @return Collection<Warehouse>
     */
    public function findByMarketAndStore(string $market, string $store = '*'): Collection
    {
        return $this->repository->findByMarketAndStore($market, $store);
    }

    /**
     * 为仓库添加市场/门店关联
     *
     * @param Warehouse $warehouse 仓库
     * @param string $market 市场
     * @param string $store 门店
     * @param bool $isPrimary 是否主要市场/门店
     * @return WarehouseMarket
     */
    public function addMarketToWarehouse(
        Warehouse $warehouse,
        string $market,
        string $store = '*',
        bool $isPrimary = false
    ): WarehouseMarket {
        // 检查是否已存在
        $warehouseMarket = WarehouseMarket::query()
            ->where('warehouse_id', $warehouse->id)
            ->where('market', $market)
            ->where('store', $store)
            ->first();

        if ($warehouseMarket) {
            // 更新现有记录
            $warehouseMarket->is_active = true;
            $warehouseMarket->is_primary = $isPrimary;
            $warehouseMarket->save();
            return $warehouseMarket;
        }

        // 创建新记录
        $warehouseMarket = new WarehouseMarket();
        $warehouseMarket->warehouse_id = $warehouse->id;
        $warehouseMarket->market = $market;
        $warehouseMarket->store = $store;
        $warehouseMarket->is_active = true;
        $warehouseMarket->is_primary = $isPrimary;
        $warehouseMarket->save();

        return $warehouseMarket;
    }

    /**
     * 移除仓库的市场/门店关联
     *
     * @param Warehouse $warehouse 仓库
     * @param string $market 市场
     * @param string $store 门店
     * @return bool
     */
    public function removeMarketFromWarehouse(
        Warehouse $warehouse,
        string $market,
        string $store = '*'
    ): bool {
        $warehouseMarket = WarehouseMarket::query()
            ->where('warehouse_id', $warehouse->id)
            ->where('market', $market)
            ->where('store', $store)
            ->first();

        if ($warehouseMarket) {
            $warehouseMarket->is_active = false;
            $warehouseMarket->save();
            return true;
        }

        return false;
    }

    /**
     * 设置仓库为默认仓库
     *
     * @param Warehouse $warehouse 仓库
     * @return void
     */
    public function setAsDefault(Warehouse $warehouse): void
    {
        // 先取消其他默认仓库
        Warehouse::query()
            ->where('is_default', true)
            ->where('id', '!=', $warehouse->id)
            ->update(['is_default' => false]);

        // 设置当前仓库为默认
        $warehouse->is_default = true;
        $warehouse->save();
    }
}

