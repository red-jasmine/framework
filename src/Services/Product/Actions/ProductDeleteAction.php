<?php

namespace RedJasmine\Product\Services\Product\Actions;

use RedJasmine\Product\Models\Product;
use RedJasmine\Support\Foundation\Service\Actions\ResourceDeleteAction;

/**
 * @property Product $model
 */
class ProductDeleteAction extends ResourceDeleteAction
{

    protected ?bool $hasDatabaseTransactions = true;

    public function handle() : ?bool
    {
        $product = $this->model;
        $product->info->delete();
        $product->skus()->delete();
        return $product->delete();
    }


}
