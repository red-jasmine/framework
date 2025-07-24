<?php

namespace RedJasmine\PointsMall\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\Product\ProductAmountInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductIdentity;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\PointsMall\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Throwable;
use RedJasmine\Product\Exceptions\ProductException;

/**
 * 积分商城商品服务集成
 * 对接商品领域的应用服务
 */
class ProductServiceIntegration implements ProductServiceInterface
{
    public function __construct(
        protected ProductApplicationService $productApplicationService,
    ) {
    }

    public function getProductInfo(ProductIdentity $product) : ProductInfo
    {
        $productInfo              = new ProductInfo();
        $productInfo->product     = $product;
        $productInfo->isAvailable = false;

        try {
            $productModel = $this->getProduct($product);


            // 设置商品基础信息
            $productInfo->product->seller = $productModel->owner;
            $productInfo->title           = $productModel->title;
            $productInfo->image           = $productModel->image;
            $productInfo->maxLimit        = $productModel->max_limit;
            $productInfo->minLimit        = $productModel->min_limit;
            $productInfo->stepLimit       = $productModel->step_limit;
            $productInfo->propertiesName  = '';
            $productInfo->productType     = $productModel->product_type;
            $productInfo->shippingTypes   = $productModel->getAllowShippingTypes();
            $productInfo->brandId         = $productModel->brand_id;
            $productInfo->categoryId      = $productModel->category_id;
            $productInfo->productGroupId  = $productModel->product_group_id;
            $productInfo->isAvailable     = $productModel->isAllowSale();

        } catch (Throwable $throwable) {
            throw $throwable;
        }

        return $productInfo;
    }


    /**
     * 获取积分商品信息
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return ProductInfo
     * @throws Throwable
     * @throws ProductException
     */
    public function getProductInfos(ProductPurchaseFactor $productPurchaseFactor) : ProductInfo
    {
        $productInfo              = new ProductInfo();
        $productInfo->product     = $productPurchaseFactor->product;
        $productInfo->isAvailable = false;

        try {
            $productModel = $this->getProduct($productPurchaseFactor->product);
            $sku          = $productModel->getSkuBySkuId($productPurchaseFactor->product->skuId);

            // 设置商品基础信息
            $productInfo->product->seller = $productModel->owner;
            $productInfo->title           = $productModel->title;
            $productInfo->image           = $productModel->image;
            $productInfo->maxLimit        = $productModel->max_limit;
            $productInfo->minLimit        = $productModel->min_limit;
            $productInfo->stepLimit       = $productModel->step_limit;
            $productInfo->propertiesName  = $sku->properties_name;
            $productInfo->productType     = $productModel->product_type;
            $productInfo->shippingTypes   = $productModel->getAllowShippingTypes();
            $productInfo->brandId         = $productModel->brand_id;
            $productInfo->categoryId      = $productModel->category_id;
            $productInfo->productGroupId  = $productModel->product_group_id;
            $productInfo->isAvailable     = $productModel->isAllowSale();

        } catch (Throwable $throwable) {
            throw $throwable;
        }

        return $productInfo;
    }

    /**
     * 获取积分商品价格信息
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return ProductAmountInfo
     */
    public function getProductAmount(ProductPurchaseFactor $productPurchaseFactor) : ProductAmountInfo
    {
        $productAmount             = $this->productApplicationService->getProductPrice($productPurchaseFactor);
        $productAmount->quantity   = $productPurchaseFactor->quantity;
        $productAmount->totalPrice = $productAmount->price->multiply($productPurchaseFactor->quantity);

        return $productAmount;
    }

    /**
     * 验证积分商品是否存在
     *
     * @param  string  $productType
     * @param  string  $productId
     *
     * @return bool
     */
    public function validateProduct(string $productType, string $productId) : bool
    {
        try {
            $query = FindQuery::from([]);
            $query->setKey($productId);
            $product = $this->productApplicationService->find($query);
            return $product !== null;
        } catch (Throwable $throwable) {
            return false;
        }
    }

    /**
     * 同步积分商品信息
     *
     * @param  string  $productType
     * @param  string  $productId
     *
     * @return array|null
     */
    public function syncProductInfo(string $productType, string $productId) : ?array
    {
        try {
            $query = FindQuery::from([]);
            $query->setKey($productId);
            $product = $this->productApplicationService->find($query);

            if (!$product) {
                return null;
            }

            return [
                'title'          => $product->title,
                'description'    => $product->description,
                'image'          => $product->image,
                'price_currency' => $product->price_currency,
                'price_amount'   => $product->price_amount,
            ];
        } catch (Throwable $throwable) {
            return null;
        }
    }

    /**
     * 获取积分商品库存信息
     *
     * @param  string  $productType
     * @param  string  $productId
     *
     * @return array|null
     */
    public function getProductStock(string $productType, string $productId) : ?array
    {
        try {
            $query = FindQuery::from([]);
            $query->setKey($productId);
            $product = $this->productApplicationService->find($query);

            if (!$product) {
                return null;
            }

            return [
                'stock'           => $product->stock,
                'lock_stock'      => $product->lock_stock,
                'safety_stock'    => $product->safety_stock,
                'available_stock' => $product->getAvailableStock(),
            ];
        } catch (Throwable $throwable) {
            return null;
        }
    }

    /**
     * 验证积分商品状态
     *
     * @param  string  $productType
     * @param  string  $productId
     *
     * @return bool
     */
    public function validateProductStatus(string $productType, string $productId) : bool
    {
        try {
            $query = FindQuery::from([]);
            $query->setKey($productId);
            $product = $this->productApplicationService->find($query);

            return $product && $product->isAllowSale();
        } catch (Throwable $throwable) {
            return false;
        }
    }

    /**
     * 获取商品模型
     *
     * @param  ProductIdentity  $product
     *
     * @return Product
     */
    protected function getProduct(ProductIdentity $product) : Product
    {
        $query = FindQuery::from([]);
        $query->setKey($product->id);
        $query->include = ['skus'];
        return $this->productApplicationService->find($query);
    }
} 