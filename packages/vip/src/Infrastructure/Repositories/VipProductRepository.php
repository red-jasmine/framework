<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;
use RedJasmine\Vip\Infrastructure\ProductDomainConverter;

class VipProductRepository implements VipProductRepositoryInterface
{

    public function __construct(
        public ProductApplicationService $productCommandService,
        public ProductDomainConverter $productDomainConverter,
    ) {
    }

    protected static string $eloquentModelClass = VipProduct::class;


    public function find($id) : VipProduct
    {
        // 调用商品领域 进行查询
        $productModel = $this->productCommandService->find(FindQuery::from(['id' => $id]));

        return $this->productDomainConverter->converter($productModel);

    }

    /**
     * @param  Model|VipProduct  $model
     *
     * @return VipProduct
     */
    public function store(Model $model) : VipProduct
    {

        $command = ProductCreateCommand::from([
            'owner'         => $this->productDomainConverter->seller(),
            'title'         => $model->name,
            'price'         => $model->price->toArray(),
            'productType'   => ProductTypeEnum::VIRTUAL,
            'unit'          => $model->time_unit->value,
            'unitQuantity'  => $model->time_value,
            'stock'         => $model->stock,
            'biz'         => $model->biz,
            'product_model' => $model->type, // 产品型号 对应的是 VIP 类型
            'extra'         => [
                'biz' => $model->biz,
                'type'   => $model->type,
            ],
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
            'owner'         => $this->productDomainConverter->seller(),
            'title'         => $model->name,
            'price'         => $model->price->toArray(),
            'productType'   => ProductTypeEnum::VIRTUAL,
            'unit'          => $model->time_unit->value,
            'unitQuantity'  => $model->time_value,
            'stock'         => $model->stock,
            'biz'         => $model->biz,
            'product_model' => $model->type, // 产品型号 对应的是 VIP 类型
            'extra'         => [
                'biz' => $model->biz,
                'type'   => $model->type,
            ],
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


}