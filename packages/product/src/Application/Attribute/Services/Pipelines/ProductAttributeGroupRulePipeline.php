<?php

namespace RedJasmine\Product\Application\Attribute\Services\Pipelines;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeGroupRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductAttributeException;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductAttributeGroupRulePipeline
{
    public function __construct(
        protected ProductAttributeGroupRepositoryInterface $repository,
    ) {
    }


    /**
     * @throws ProductAttributeException
     */
    public function handle(Data $command, \Closure $next, string $attributeName = 'groupId') : mixed
    {
        // 属于业务规则 不应该放在这里 TODO
        $groupId = $command->{$attributeName};
        if ($groupId) {
            try {
                $this->repository->findByQuery(FindQuery::from(['id'=>$groupId]));
            } catch (ModelNotFoundException) {
                throw new ProductAttributeException('属性组不存在:'.$groupId);
            }

        }
        return $next($command);
    }
}
