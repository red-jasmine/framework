<?php

namespace RedJasmine\Product\Application\Property\Services\Pipelines;

use Closure;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyReadRepositoryInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductPropertyRulePipeline
{
    public function __construct(
        protected ProductPropertyReadRepositoryInterface $repository,
    ) {
    }


    public function handle(Data $command, Closure $next) : mixed
    {

        $command = $command;
        $this->repository->findById(FindQuery::make($command->pid));
        return $next($command);
    }
}
