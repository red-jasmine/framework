<?php

namespace RedJasmine\Vip\Infrastructure;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Data\UserData;
use RedJasmine\Vip\Domain\Models\VipProduct;

class ProductDomainConverter
{



    public function seller() : UserInterface
    {
        return UserData::from([
            'type' => 'vip-system',
            'id'   => 1,
        ]);
    }

    public function converter(Product $product) : VipProduct
    {
        /**
         * @var VipProduct $model
         */
        $model = VipProduct::make();

        $model->id         = $product->id;
        $model->price      = $product->price;
        $model->name       = $product->title;
        $model->time_value = $product->unit_quantity;
        $model->stock      = $product->stock;
        $model->biz     = $product->biz;
        $model->type       = $product->product_model;
        $model->time_unit  = TimeUnitEnum::from($product->unit);
        return $model;
    }
}