<?php

namespace RedJasmine\Card\Application\Services\Pipelines;

use RedJasmine\Card\Application\Services\CardGroupQueryService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CardGroupPipeline
{
    public function __construct(
        protected CardGroupQueryService $groupQueryService
    )
    {
        parent::__construct();
    }


    public function handle(CommandHandler $handler, \Closure $next) : mixed
    {
        $command = $handler->getArguments()[0];


        if ($command->groupId) {
            $this->groupQueryService->getRepository()->withQuery(function ($query) use ($command) {

                $query->onlyOwner($command->owner);
            });

            $this->groupQueryService->findById(FindQuery::make($command->groupId));
        }


        return $next($handler);
    }
}
