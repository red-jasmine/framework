<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockLogReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductStockLogReadRepository extends QueryBuilderReadRepository implements ProductStockLogReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductStockLog::class;


}
