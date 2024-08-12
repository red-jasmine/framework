<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductSkuReadRepository extends QueryBuilderReadRepository implements ProductSkuReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductSku::class;

    /**
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findList(array $ids)
    {
       return $this->query()->whereIn('id', $ids)->get();
    }


}
