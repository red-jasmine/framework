<?php

namespace RedJasmine\Card\Infrastructure\Repositories;

use RedJasmine\Card\Domain\Models\CardGroup;
use RedJasmine\Card\Domain\Repositories\CardGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 卡密分组仓库实现
 *
 * 基于Repository实现，提供卡密分组实体的读写操作能力
 */
class CardGroupRepository extends Repository implements CardGroupRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = CardGroup::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::partial('name'),
        ];
    }
}
