<?php

namespace RedJasmine\Product\Application\Property\Services\Pipelines;

use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;

class ProductPropertyGroupRulePipeline
{
    public function __construct(
        protected ProductPropertyGroupRepositoryInterface $repository,
    )
    {
    }


    public function handle(CommandHandler $handler, \Closure $next) : mixed
    {
        /// TODO
        $command = $handler->getArguments()[0];
        if ($command->groupId) {
            $this->repository->find($command->groupId);
        }
        return $next($handler);
    }
}
