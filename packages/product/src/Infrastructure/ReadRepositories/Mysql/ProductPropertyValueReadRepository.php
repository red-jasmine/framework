<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\BaseReadRepository;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductPropertyValueReadRepository extends QueryBuilderReadRepository implements ProductPropertyValueReadRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductPropertyValue::class;

    /**
     * @param int   $pid
     * @param array $ids
     *
     * @return ProductPropertyValue[]|null
     */
    public function findByIdsInProperty(int $pid, array $ids)
    {
        return static::$modelClass::query()->whereIn("id", $ids)->where("pid", $pid)->get();
    }


}
