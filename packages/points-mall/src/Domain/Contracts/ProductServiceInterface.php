<?php

namespace RedJasmine\PointsMall\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\Product\ProductAmountInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;

/**
 * 积分商城商品服务接口
 * - 获取积分商品信息
 * - 获取积分商品价格信息
 * - 验证积分商品状态
 */
interface ProductServiceInterface
{
    /**
     * 获取积分商品信息
     *
     * @param ProductPurchaseFactor $productPurchaseFactor
     * @return ProductInfo
     */
    public function getProductInfo(ProductPurchaseFactor $productPurchaseFactor): ProductInfo;

    /**
     * 获取积分商品价格信息
     *
     * @param ProductPurchaseFactor $productPurchaseFactor
     * @return ProductAmountInfo
     */
    public function getProductAmount(ProductPurchaseFactor $productPurchaseFactor): ProductAmountInfo;

    /**
     * 验证积分商品是否存在
     *
     * @param string $productType 商品类型
     * @param string $productId 商品ID
     * @return bool
     */
    public function validateProduct(string $productType, string $productId): bool;

    /**
     * 同步积分商品信息
     *
     * @param string $productType 商品类型
     * @param string $productId 商品ID
     * @return array|null
     */
    public function syncProductInfo(string $productType, string $productId): ?array;

    /**
     * 获取积分商品库存信息
     *
     * @param string $productType 商品类型
     * @param string $productId 商品ID
     * @return array|null
     */
    public function getProductStock(string $productType, string $productId): ?array;

    /**
     * 验证积分商品状态
     *
     * @param string $productType 商品类型
     * @param string $productId 商品ID
     * @return bool
     */
    public function validateProductStatus(string $productType, string $productId): bool;
} 