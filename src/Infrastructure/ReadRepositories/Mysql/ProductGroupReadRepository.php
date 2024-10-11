<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductGroupReadRepository extends QueryBuilderReadRepository implements ProductGroupReadRepositoryInterface
{
    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductGroup::class;


    public function findByName($name) : ?ProductGroup
    {
        return $this->query()->where('name', $name)->first();
    }


}
