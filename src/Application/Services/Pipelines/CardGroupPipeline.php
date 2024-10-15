<?php

namespace RedJasmine\Card\Application\Services\Pipelines;

use RedJasmine\Card\Application\Services\CardGroupQueryService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CardGroupPipeline
{
    public function __construct(
        protected CardGroupQueryService $groupQueryService
    )
    {

    }


    public function handle(Data $command, \Closure $next) : mixed
    {

        if ($command->groupId) {
            $this->groupQueryService->getRepository()->withQuery(function ($query) use ($command) {

                $query->onlyOwner($command->owner);
            });

            $this->groupQueryService->findById(FindQuery::make($command->groupId));
        }

        return $next($command);
    }
}
