<?php

namespace RedJasmine\Interaction\Infrastructure\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use RedJasmine\Interaction\Domain\Facades\InteractionType;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 互动记录仓库实现
 *
 * 基于Repository实现，提供互动记录实体的读写操作能力
 */
class InteractionRecordRepository extends Repository implements InteractionRecordRepositoryInterface
{
    protected static string $modelClass = InteractionRecord::class;

    public function findByInteractionType(string $interactionType, $id)
    {
        $interactionType    = InteractionType::create($interactionType);
        static::$modelClass = $interactionType->getModelClass();
        return parent::find($id);
    }

    public function query(?Query $query = null) : Builder
    {
        // TODO 需要优化
        if ($query && isset($query->interactionType)) {
            $interactionType    = InteractionType::create($query->interactionType);
            static::$modelClass = $interactionType->getModelClass();
        }
        return parent::query();
    }

    protected function buildRequest(?Query $query = null) : Request
    {
        $other = $query->other ?? [];
        foreach ($other as $name => $value) {
            $query->$name = $value;
        }

        return parent::buildRequest($query);
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        $filters = [
            AllowedFilter::exact('resource_type'),
            AllowedFilter::exact('resource_id'),
            AllowedFilter::exact('interaction_type'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('user_type'),
        ];

        if ($query && isset($query->interactionType)) {
            $interactionType = InteractionType::create($query->interactionType);
            $filters = array_merge($filters, $interactionType->allowedFields());
        }

        return $filters;
    }

    public function findByResourceUserLast(FindQuery $query) : InteractionRecord
    {
        return $this->query($query)->firstOrFail();
    }
}
