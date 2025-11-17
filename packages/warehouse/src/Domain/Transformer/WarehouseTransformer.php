<?php

namespace RedJasmine\Warehouse\Domain\Transformer;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Warehouse\Domain\Data\WarehouseData;
use RedJasmine\Warehouse\Domain\Data\WarehouseMarketData;
use RedJasmine\Warehouse\Domain\Models\Warehouse;
use RedJasmine\Warehouse\Domain\Models\WarehouseMarket;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class WarehouseTransformer implements TransformerInterface
{
    /**
     * 转换数据到模型
     *
     * @param WarehouseData $data
     * @param Warehouse $model
     * @return Warehouse
     */
    public function transform($data, $model): Warehouse
    {
        $model->code = $data->code;
        $model->name = $data->name;
        $model->owner = $data->owner;
        $model->warehouse_type = $data->warehouseType;
        $model->address = $data->address;
        $model->contact_phone = $data->contactPhone;
        $model->contact_person = $data->contactPerson;
        $model->is_active = $data->isActive;
        $model->is_default = $data->isDefault;

        // 同步市场/门店关联
        $this->syncMarkets($model, $data->markets);

        return $model;
    }


    /**
     * 保存市场/门店关联（在仓库保存后调用）
     *
     * @param Warehouse $warehouse
     * @param WarehouseMarketData[] $marketsData
     * @return void
     */
    public function syncMarkets(Warehouse $warehouse,array $marketsData): void
    {
        // 查询所有现有关联（包括已软删除的）
        $existingMarkets = WarehouseMarket::withTrashed()
            ->where('warehouse_id', $warehouse->id)
            ->get()
            ->keyBy(function ($market) {
                return $market->market . '|' . $market->store;
            });

        // 先标记所有现有关联为删除状态
        $existingMarkets->each(function (WarehouseMarket $warehouseMarket) {
            $warehouseMarket->deleted_at = now();
            return $warehouseMarket;
        });

        // 处理新的关联数据
        $warehouseMarketModels = [];
        foreach ($marketsData as $marketData) {
            $market = $marketData instanceof WarehouseMarketData
                ? $marketData->market
                : ($marketData['market'] ?? '');
            $store = $marketData instanceof WarehouseMarketData
                ? $marketData->store
                : ($marketData['store'] ?? '');
            $isActive = $marketData instanceof WarehouseMarketData
                ? $marketData->isActive
                : ($marketData['is_active'] ?? true);
            $isPrimary = $marketData instanceof WarehouseMarketData
                ? $marketData->isPrimary
                : ($marketData['is_primary'] ?? false);

            $key = $market . '|' . $store;

            // 查找现有记录
            $warehouseMarketModel = $existingMarkets->get($key);

            if (!$warehouseMarketModel) {
                // 创建新模型
                $warehouseMarketModel = new WarehouseMarket();
            }

            // 设置或更新字段
            $warehouseMarketModel->warehouse_id = $warehouse->id;
            $warehouseMarketModel->market = $market;
            $warehouseMarketModel->store = $store;
            $warehouseMarketModel->is_active = $isActive;
            $warehouseMarketModel->is_primary = $isPrimary;
            $warehouseMarketModel->deleted_at = null; // 恢复或设置为未删除

            $warehouseMarketModels[] = $warehouseMarketModel;
        }



        // 清除临时属性
        $warehouse->setRelation('markets',Collection::make($warehouseMarketModels));
    }
}

