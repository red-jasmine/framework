<?php

namespace RedJasmine\Product\Application\Attribute\Services\Pipelines;


use Closure;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValueApplicationService;
use RedJasmine\Product\Exceptions\ProductPropertyException;

class ProductAttributeValueUpdatePipeline
{
    public function __construct(
        protected ProductAttributeValueApplicationService $service,
    ) {
    }


    /**
     * @param  ProductAttributeValueUpdateCommand  $command
     * @param  Closure  $next
     *
     * @return mixed
     * @throws ProductPropertyException
     */
    public function handle(ProductAttributeValueUpdateCommand $command, Closure $next) : mixed
    {

        $hasRepeatCount = $this->service
            ->repository->query()
                            ->where('id', '<>', $command->id)
                            ->where('name', $command->name)
                            ->where('pid', $command->pid)
                            ->count();

        if ($hasRepeatCount > 0) {
            throw new ProductPropertyException('Property Value Update Failed:'.$command->name);
        }
        return $next($command);
    }


}
