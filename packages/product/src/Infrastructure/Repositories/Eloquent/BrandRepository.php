<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;


class BrandRepository extends EloquentRepository implements BrandRepositoryInterface
{
    protected static string $eloquentModelClass = Brand::class;

    public function findByName($name) : ?Brand
    {
        return static::$eloquentModelClass::where('name', $name)->first();
    }


}
