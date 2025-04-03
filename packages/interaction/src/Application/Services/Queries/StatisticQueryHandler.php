<?php

namespace RedJasmine\Interaction\Application\Services\Queries;

use Illuminate\Support\Collection;
use RedJasmine\Interaction\Application\Services\InteractionRecordApplicationService;
use RedJasmine\Interaction\Domain\Facades\InteractionResource;
use RedJasmine\Support\Application\Queries\QueryHandler;

class StatisticQueryHandler extends QueryHandler
{

    public function __construct(
        protected InteractionRecordApplicationService $service,

    ) {
    }

    public function handle(StatisticQuery $query) : Collection
    {

        $resource = InteractionResource::create($query->resourceType);
        $query->setIsWithCount(false);

        $result = $this->service->statisticReadRepository->paginate($query);
        $result = $result->pluck('quantity', 'interaction_type');
        foreach ($resource->allowInteractionType() as $value) {
            $result[$value] = $result[$value] ?? 0;
        }

        return $result;

    }

}