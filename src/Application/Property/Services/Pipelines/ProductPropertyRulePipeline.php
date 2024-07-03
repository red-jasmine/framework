<?php

namespace RedJasmine\Product\Application\Property\Services\Pipelines;

use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;

class ProductPropertyRulePipeline
{
    public function __construct(
        protected ProductPropertyRepositoryInterface $repository,
    )
    {
    }


    public function handle(CommandHandler $handler, \Closure $next) : mixed
    {
        $command = $handler->getArguments()[0];
        $this->repository->find($command->pid);
        return $next($handler);
    }
}
