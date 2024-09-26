<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductSellerCategoryReadRepository extends QueryBuilderReadRepository implements ProductSellerCategoryReadRepositoryInterface
{
    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductSellerCategory::class;


    public function findByName($name) : ?ProductSellerCategory
    {
        return $this->query()->where('name', $name)->first();
    }


}
