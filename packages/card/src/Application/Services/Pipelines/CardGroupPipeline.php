<?php

namespace RedJasmine\Card\Application\Services\Pipelines;

use RedJasmine\Card\Application\Services\CardGroupApplicationService;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CardGroupPipeline
{
    public function __construct(
        protected CardGroupApplicationService $groupQueryService
    ) {
    }

    public function handle(Data $command, \Closure $next) : mixed
    {
        if ($command->groupId) {
            $this->groupQueryService->repository->withQuery(function ($query) use ($command) {
                $query->onlyOwner($command->owner);
            });

            $this->groupQueryService->find(FindQuery::make($command->groupId));
        }

        return $next($command);
    }
}
