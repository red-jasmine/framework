<?php

namespace RedJasmine\Product\Tests\Fixtures\Product;

use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\SubStockTypeEnum;
use RedJasmine\Product\Tests\Fixtures\Users\User;
use RedJasmine\Support\Contracts\UserInterface;

class ProductFaker
{


    public function seller() : UserInterface
    {
        return User::make(1);
    }

    protected function data() : array
    {

        return [
            'owner'               => $this->seller(),
            'title'               => fake()->text(),
            'product_type'        => ProductTypeEnum::VIRTUAL->value,
            'shipping_type'       => ShippingTypeEnum::DUMMY->value,
            'status'              => ProductStatusEnum::ON_SALE->value,
            'freight_payer'       => FreightPayerEnum::SELLER->value,
            'sub_stock'           => SubStockTypeEnum::DEFAULT->value,
            'price'               => fake()->numberBetween(100, 1000),
            'market_price'        => fake()->numberBetween(100, 1000),
            'cost_price'          => fake()->numberBetween(100, 1000),
            'stock'               => fake()->numberBetween(1, 10),
            'safety_stock'        => fake()->numberBetween(10, 20),
            'delivery_time'       => fake()->randomElement([ 0, 2, 12, 24, 48, 72 ]),
            'unit '               => 1,
            'image'               => fake()->imageUrl(),
            'barcode'             => fake()->numerify('#########'),
            'outer_id'            => fake()->text(),
            'sort'                => fake()->numberBetween(1, 100),
            'im_multiple_spec'    => false,
            'brand_id'            => 0,
            'category_id'         => 0,
            'product_group_id'  => 0,
            'postage_id'          => 0,
            'min_limit'           => 0,
            'max_limit'           => 0,
            'stop_limit'          => 1,
            'vip'                 => 0,
            'points'              => fake()->numberBetween(100, 1000),
            'is_hot'              => fake()->boolean(),
            'is_new'              => fake()->boolean(),
            'is_best'             => fake()->boolean(),
            'is_benefit'          => fake()->boolean(),
            'keywords'            => fake()->words(5, true),
            'description'         => fake()->text(),
            'detail'              => fake()->randomHtml(),
            'images'              => [ fake()->imageUrl(), fake()->imageUrl(), ],
            'videos'              => [],
            'length'              => fake()->numberBetween(100, 1000),
            'width'               => fake()->numberBetween(100, 1000),
            'height'              => fake()->numberBetween(100, 1000),
            'weight'              => fake()->numberBetween(100, 1000),
            'size'                => fake()->numberBetween(100, 1000),
            'remarks'             => fake()->text(),
            'supplier'            => [ 'type' => 'supplier', 'id' => fake()->numberBetween(100, 1000) ],
            'supplier_product_id' => fake()->numerify('#########'),
            'tools'               => [],
            'expands'             => []
        ];
    }


    public function createCommand(array $data = []) : ProductCreateCommand
    {

        return ProductCreateCommand::from(array_merge($this->data(), $data));
    }


    public function updateCommand(array $data = []) : ProductUpdateCommand
    {
        return ProductUpdateCommand::from(array_merge($this->data(), $data));
    }

}
