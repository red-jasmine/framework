<?php

namespace RedJasmine\Interaction\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use RedJasmine\Interaction\Application\Services\Queries\FindQuery;
use RedJasmine\Interaction\Domain\Facades\InteractionType;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class InteractionRecordReadRepository extends QueryBuilderReadRepository
    implements InteractionRecordReadRepositoryInterface
{
    protected static string $modelClass = InteractionRecord::class;


    public function query(?Query $query = null) : Builder
    {
        // TODO 需要优化
        $interactionType    = InteractionType::create($query->interactionType);
        static::$modelClass = $interactionType->getModelClass();
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


    public function allowedFilters(?Query $query = null) : array
    {
        $interactionType = InteractionType::create($query->interactionType);

        return [
            ...$interactionType->allowedFields(),
            AllowedFilter::exact('resource_type'),
            AllowedFilter::exact('resource_id'),
            AllowedFilter::exact('interaction_type'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('user_type'),
        ];
    }

    public function findByResourceUserLast(\RedJasmine\Support\Domain\Data\Queries\FindQuery $query) : InteractionRecord
    {
        return $this->query($query)->firstOrFail();
    }


}