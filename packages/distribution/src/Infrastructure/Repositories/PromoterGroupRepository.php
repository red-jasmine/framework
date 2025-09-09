<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterGroup;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 推广员分组仓库实现
 *
 * 基于Repository实现，提供推广员分组实体的读写操作能力
 */
class PromoterGroupRepository extends Repository implements PromoterGroupRepositoryInterface
{
    use HasTree;
    
    protected static string $modelClass = PromoterGroup::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('status'),
        ];
    }
}