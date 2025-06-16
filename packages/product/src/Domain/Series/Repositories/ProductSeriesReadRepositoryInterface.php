<?php

namespace RedJasmine\Product\Domain\Series\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductSeriesReadRepositoryInterface extends ReadRepositoryInterface
{


    /**
     * @param $productId
     *
     * @return ProductSeries
     * @throws ModelNotFoundException
     */
    public function findProductSeries($productId) : ProductSeries;
}
