<?php

namespace RedJasmine\Warehouse\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Warehouse\Domain\Models\Warehouse;
use RedJasmine\Warehouse\Domain\Repositories\WarehouseRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 仓库仓库实现
 *
 * 基于Repository实现，提供仓库实体的读写操作能力
 */
class WarehouseRepository extends Repository implements WarehouseRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Warehouse::class;

    /**
     * 根据编码查找仓库
     */
    public function findByCode(string $code): ?Warehouse
    {
        return $this->query()->where('code', $code)->first();
    }

    /**
     * 获取默认仓库
     */
    public function getDefaultWarehouse(): ?Warehouse
    {
        return $this->query()
            ->where('is_default', true)
            ->where('is_active', true)
            ->first();
    }

    /**
     * 根据市场/门店查找仓库列表
     */
    public function findByMarketAndStore(string $market, string $store = '*'): Collection
    {
        return $this->query()
            ->whereHas('markets', function ($query) use ($market, $store) {
                $query->where('market', $market)
                    ->where(function ($q) use ($store) {
                        $q->where('store', $store)
                            ->orWhere('store', '*');
                    })
                    ->where('is_active', true);
            })
            ->where('is_active', true)
            ->with('markets')
            ->get();
    }

    /**
     * 根据类型查找仓库列表
     */
    public function findByType(string $type): Collection
    {
        return $this->query()
            ->where('warehouse_type', $type)
            ->where('is_active', true)
            ->get();
    }

    /**
     * 获取所有启用的仓库
     */
    public function getActiveWarehouses(): Collection
    {
        return $this->query()
            ->where('is_active', true)
            ->get();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::partial('code'),
            AllowedFilter::partial('name'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('warehouse_type'),
            AllowedFilter::exact('is_active'),
            AllowedFilter::exact('is_default'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('code'),
            AllowedSort::field('name'),
            AllowedSort::field('warehouse_type'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }
}

