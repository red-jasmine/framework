<?php

namespace RedJasmine\Product\Application\Property\Services\Pipelines;


use Closure;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueQueryService;
use RedJasmine\Product\Exceptions\ProductPropertyException;

class PropertyValueUpdatePipeline
{
    public function __construct(
        protected ProductPropertyValueQueryService $queryService,
    ) {
    }


    /**
     * @param  ProductPropertyValueUpdateCommand  $command
     * @param  Closure  $next
     *
     * @return mixed
     * @throws ProductPropertyException
     */
    public function handle(ProductPropertyValueUpdateCommand $command, Closure $next) : mixed
    {

        $hasRepeatCount = $this->queryService
            ->getRepository()->query()
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
