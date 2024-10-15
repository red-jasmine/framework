<?php

namespace RedJasmine\Product\Application\Series\Services\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\AbstractException;

class SeriesProductPipeline
{
    public function __construct(
        protected ProductQueryService $queryService
    )
    {
    }


    /**
     * @param ProductSeriesCreateCommand $command
     * @param Closure $next
     *
     * @return mixed
     * @throws AbstractException
     * @throws ProductException
     */
    public function handle(ProductSeriesCreateCommand $command, Closure $next) : mixed
    {


        $this->queryService->getRepository()->withQuery(function ($query) use ($command) {
            return $query->onlyOwner($command->owner);
        });

        //验证商品是否存在
        try {
            if ($command->products) {
                foreach ($command->products as $product) {
                    $this->queryService->findById(FindQuery::make($product->productId));
                }
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            throw new ProductException('产品不存在');
        }

        return $next($command);
    }
}
