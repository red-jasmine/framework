<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductServiceReadRepository extends QueryBuilderReadRepository implements ProductServiceReadRepositoryInterface
{


    /**
     * @template  T
     * @var class-string<T> $modelClass
     */
    protected static string $modelClass = ProductService::class;

    public function findByName($name) : ?ProductService
    {
        return $this->query()->where('name', $name)->first();
    }

}
