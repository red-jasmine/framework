<?php

namespace RedJasmine\Product\Application\Attribute\Services\Pipelines;


use Closure;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValueApplicationService;
use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeValueData;
use RedJasmine\Product\Exceptions\ProductAttributeException;

class ProductAttributeValueUpdatePipeline
{
    public function __construct(
        protected ProductAttributeValueApplicationService $service,
    ) {
    }


    /**
     * @param  ProductAttributeValueData  $command
     * @param  Closure  $next
     *
     * @return mixed
     * @throws ProductAttributeException
     */
    public function handle(ProductAttributeValueData $command, Closure $next) : mixed
    {
        // 属于业务规则 不应该放在这里 TODO

        $hasRepeatCount = $this->service
            ->repository->query()
                        ->where('id', '<>', $command->id)
                        ->where('name', $command->name)
                        ->where('aid', $command->aid)
                        ->count();

        if ($hasRepeatCount > 0) {
            throw new ProductAttributeException('Attribute Value Update Failed:'.$command->name);
        }
        return $next($command);
    }


}
