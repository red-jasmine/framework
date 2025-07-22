<?php

namespace RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PointsProductRepository extends EloquentRepository implements PointsProductRepositoryInterface
{
    /**
     * @var $eloquentModelClass class-string
     */
    protected static string $eloquentModelClass = PointsProduct::class;

    /**
     * 根据ID查找积分商品
     */
    public function find($id): ?PointsProduct
    {
        return static::$eloquentModelClass::find($id);
    }

    /**
     * 减少库存
     */
    public function decreaseStock(string $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }

        return $product->decreaseStock($quantity);
    }

    /**
     * 查找上架商品
     */
    public function findOnSale(): \Illuminate\Database\Eloquent\Collection
    {
        return static::$eloquentModelClass::where('status', 'on_sale')
            ->where('stock', '>', 0)
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 根据分类查找商品
     */
    public function findByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection
    {
        return static::$eloquentModelClass::where('category_id', $categoryId)
            ->where('status', 'on_sale')
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 根据商品源查找商品
     */
    public function findByProductSource(string $productType, string $productId): ?PointsProduct
    {
        return static::$eloquentModelClass::where('product_type', $productType)
            ->where('product_id', $productId)
            ->first();
    }
} 