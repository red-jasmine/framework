<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;


class BrandRepository implements BrandRepositoryInterface
{
    public function find(int $id) : Brand
    {
        return Brand::findOrFail($id);
    }

    public function store(Brand $brand) : Brand
    {
        try {
            DB::beginTransaction();
            $brand->push();
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

        return $brand;
    }

    public function update(Brand $brand) : void
    {
        try {
            DB::beginTransaction();
            $brand->push();
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

    }


}
