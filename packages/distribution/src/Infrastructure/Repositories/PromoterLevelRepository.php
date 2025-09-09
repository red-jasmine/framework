<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 推广员等级仓库实现
 *
 * 基于Repository实现，提供推广员等级实体的读写操作能力
 */
class PromoterLevelRepository extends Repository implements PromoterLevelRepositoryInterface
{
    protected static string $modelClass = PromoterLevel::class;

    /**
     * 根据等级查找推广员等级
     */
    public function findLevel(int $level): PromoterLevel
    {
        return $this->query()->where('level', $level)->firstOrFail();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('level'),
            AllowedFilter::exact('status'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('level'),
            AllowedSort::field('name'),
            AllowedSort::field('created_at'),
        ];
    }
}