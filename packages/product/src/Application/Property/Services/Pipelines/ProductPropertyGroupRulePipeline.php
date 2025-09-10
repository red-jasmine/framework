<?php

namespace RedJasmine\Product\Application\Property\Services\Pipelines;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductPropertyGroupRulePipeline
{
    public function __construct(
        protected ProductPropertyGroupRepositoryInterface $repository,
    ) {
    }


    /**
     * @throws ProductPropertyException
     */
    public function handle(Data $command, \Closure $next, string $attributeName = 'groupId') : mixed
    {

        $groupId = $command->{$attributeName};
        if ($groupId) {
            try {
                $this->repository->findByQuery(FindQuery::from(['id'=>$groupId]));
            } catch (ModelNotFoundException) {
                throw new ProductPropertyException('属性组不存在:'.$groupId);
            }

        }
        return $next($command);
    }
}
