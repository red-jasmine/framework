<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    protected static string $eloquentModelClass = Product::class;


    /**
     * @param Product $model
     *
     * @return void
     * @throws \Throwable
     */
    public function delete(Model $model)
    {

        try {
            DB::beginTransaction();
            $model->info->delete();
            $model->skus()->delete();
            $model->delete();
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


    }
}
