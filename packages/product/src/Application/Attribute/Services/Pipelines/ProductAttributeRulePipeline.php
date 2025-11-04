<?php

namespace RedJasmine\Product\Application\Attribute\Services\Pipelines;

use Closure;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeRepositoryInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductAttributeRulePipeline
{
    public function __construct(
        protected ProductAttributeRepositoryInterface $repository,
    )
    {
    }


    public function handle(Data $command, Closure $next) : mixed
    {
        $command = $command;
        $this->repository->findByQuery(FindQuery::from(['id' => $command->aid]));
        return $next($command);
    }
}
