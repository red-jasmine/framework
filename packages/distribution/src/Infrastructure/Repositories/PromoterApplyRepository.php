<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 推广员申请仓库实现
 *
 * 基于Repository实现，提供推广员申请实体的读写操作能力
 */
class PromoterApplyRepository extends Repository implements PromoterApplyRepositoryInterface
{
    protected static string $modelClass = PromoterApply::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
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
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }
}