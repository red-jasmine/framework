<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;
use RedJasmine\Vip\Infrastructure\ProductDomainConverter;

/**
 * VIP产品仓库实现
 *
 * 基于产品领域服务实现，提供VIP产品实体的读写操作能力
 */
class VipProductRepository implements VipProductRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = VipProduct::class;

    public function __construct(
        public ProductApplicationService $productCommandService,
        public ProductDomainConverter $productDomainConverter,
    ) {
    }


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

    // 以下方法合并自 VipProductReadRepository

    /**
     * 查询构建器
     */
    public function query(?Query $query = null): Builder
    {
        return $this->productCommandService->repository->query();
    }

    /**
     * 查询构建器（带过滤）
     */
    public function queryBuilder(?Query $query = null)
    {
        return $this->productCommandService->repository->queryBuilder($query);
    }

    /**
     * 添加查询条件
     */
    public function withQuery(Closure $queryCallback): static
    {
        $this->productCommandService->repository->withQuery($queryCallback);
        return $this;
    }

    /**
     * 分页查询
     */
    public function paginate(PaginateQuery $query): LengthAwarePaginator|Paginator
    {
        $query->additional(['product_model' => $query->type ?? null]);
        if (isset($query->type)) {
            unset($query->type);
        }

        $lengthAwarePaginator = $this->productCommandService->repository->paginate($query);

        return $lengthAwarePaginator->setCollection(
            $lengthAwarePaginator->getCollection()->map(fn($item) => $this->productDomainConverter->converter($item))
        );
    }
}