<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterTeam;
use RedJasmine\Distribution\Domain\Repositories\PromoterTeamRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 推广员团队仓库实现
 *
 * 基于Repository实现，提供推广员团队实体的读写操作能力
 */
class PromoterTeamRepository extends Repository implements PromoterTeamRepositoryInterface
{
    protected static string $modelClass = PromoterTeam::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),
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
            AllowedSort::field('name'),
            AllowedSort::field('created_at'),
        ];
    }
}