<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;


class BrandRepository extends Repository implements BrandRepositoryInterface
{
    protected static string $modelClass = Brand::class;

    public function findByName($name) : ?Brand
    {
        return static::$modelClass::where('name', $name)->first();
    }


}
