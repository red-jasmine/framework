<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductTagReadRepository extends QueryBuilderReadRepository implements ProductTagReadRepositoryInterface
{


    /**
     * @template  T
     * @var class-string<T> $modelClass
     */
    protected static string $modelClass = ProductTag::class;

    public function findByName($name) : ?ProductTag
    {
        return $this->query()->where('name', $name)->first();
    }

}
