<?php

namespace RedJasmine\Product\Application\Property\Services\Pipelines;


use Closure;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueQueryService;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Application\CommandHandler;

class PropertyValueUpdatePipeline
{
    public function __construct(
        protected ProductPropertyValueQueryService $queryService,
    ) {
    }


    /**
     * @param  CommandHandler  $handler
     * @param  Closure  $next
     *
     * @return mixed
     * @throws ProductPropertyException
     */
    public function handle(CommandHandler $handler, Closure $next) : mixed
    {
        /// TODO
        $hasRepeatCount = $this->queryService
            ->getRepository()->query()
            ->where('id', '<>', $handler->getArguments()[0]->id)
            ->where('name', $handler->getArguments()[0]->name)
            // TODO 需要前置处理
            // /->where('pid', $handler->model->pid)
            ->count();

        if ($hasRepeatCount > 0) {
            throw new ProductPropertyException('Property Value Update Failed:'.$handler->getArguments()[0]->name);
        }
        return $next($handler);
    }


}
