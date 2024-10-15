<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductCategoryReadRepository extends QueryBuilderReadRepository implements ProductCategoryReadRepositoryInterface
{

    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductCategory::class;


}
