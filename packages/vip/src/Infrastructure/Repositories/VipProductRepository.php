<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;

class VipProductRepository implements VipProductRepositoryInterface
{
    public function __construct(
        public ProductCommandService $productCommandService,
        public ProductQueryService $productQueryService,
    ) {
    }

    protected static string $eloquentModelClass = VipProduct::class;


    public function find($id) : VipProduct
    {
        // 调用商品领域 进行查询
        $productModel = $this->productQueryService->findById(FindQuery::from(['id' => $id]));

        return $this->converter($productModel);

    }

    /**
     * @param  Model|VipProduct  $model
     *
     * @return VipProduct
     */
    public function store(Model $model) : VipProduct
    {

        $command = ProductCreateCommand::from([
            'appId'        => 'vip',
            'owner'        => UserData::from(['type' => 'user', 'id' => '1']),
            'title'        => $model->name,
            'price'        => $model->price->toArray(),
            'productType'  => ProductTypeEnum::VIRTUAL,
            'shippingType' => ShippingTypeEnum::DUMMY,
            'unit'         => $model->time_unit->value,
            'unitQuantity' => $model->time_value,
            'stock'        => 999999999,
        ]);

        $product = $this->productCommandService->create($command);

        $model->id = $product->id;
        return $model;

    }

    /**
     * @param  Model|VipProduct  $model
     *
     * @return Model
     */
    public function update(Model $model)
    {
        $command = ProductUpdateCommand::from([
            'appId'        => 'vip',
            'owner'        => UserData::from(['type' => 'user', 'id' => '1']),
            'title'        => $model->name,
            'price'        => $model->price->toArray(),
            'productType'  => ProductTypeEnum::VIRTUAL,
            'shippingType' => ShippingTypeEnum::DUMMY,
            'unit'         => $model->time_unit->value,
            'unitQuantity' => $model->time_value,
        ]);
        $command->setKey($model->id);
        $product = $this->productCommandService->update($command);

        $model->id = $product->id;
        return $model;
    }

    public function delete(Model $model)
    {
        // TODO 不支持操作
    }


    protected function converter(Product $product) : VipProduct
    {
        /**
         * @var VipProduct $model
         */
        $model = VipProduct::make();

        $model->id         = $product->id;
        $model->price      = $product->price;
        $model->name       = $product->title;
        $model->time_value = $product->unit_quantity;
        $model->time_unit  = TimeUnitEnum::from($product->unit);
        return $model;
    }
}