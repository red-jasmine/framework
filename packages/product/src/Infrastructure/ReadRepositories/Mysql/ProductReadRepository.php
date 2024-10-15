<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductReadRepository extends QueryBuilderReadRepository implements ProductReadRepositoryInterface
{

    public static $modelClass = Product::class;

    /**
     * @param array $ids
     *
     * @return Product[]
     */
    public function findList(array $ids)
    {
       return $this->query()->whereIn('id', $ids)->get();
    }


}
