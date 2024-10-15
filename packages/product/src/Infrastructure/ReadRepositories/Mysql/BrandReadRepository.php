<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\BaseReadRepository;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class BrandReadRepository extends QueryBuilderReadRepository implements BrandReadRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Brand::class;

}
