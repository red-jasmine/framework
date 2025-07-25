<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\Product\ProductAmountInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductIdentity;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Throwable;
use RedJasmine\Product\Exceptions\ProductException;

class ProductServiceIntegration implements ProductServiceInterface
{
    public function __construct(
        protected ProductApplicationService $productApplicationService,
    ) {
    }

    /**
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return ProductInfo
     * @throws Throwable
     * @throws ProductException
     */
    public function getProductInfo(ProductPurchaseFactor $productPurchaseFactor) : ProductInfo
    {
        $productInfo              = new ProductInfo();
        $productInfo->product     = $productPurchaseFactor->product;
        $productInfo->isAvailable = false;

        try {
            $productModel = $this->getProduct($productPurchaseFactor->product);

            $sku = $productModel->getSkuBySkuId($productPurchaseFactor->product->skuId);

            $productInfo->product->seller = $productModel->owner;
            $productInfo->title           = $productModel->title;
            $productInfo->image           = $productModel->image;
            $productInfo->maxLimit        = $productModel->max_limit;
            $productInfo->minLimit        = $productModel->min_limit;
            $productInfo->stepLimit       = $productModel->step_limit;
            $productInfo->propertiesName  = $sku->properties_name;
            // 获取可选的发货类型
            $productInfo->productType      = $productModel->product_type;
            $productInfo->shippingTypes    = $productModel->getAllowShippingTypes();
            $productInfo->brandId          = $productModel->brand_id;
            $productInfo->categoryId       = $productModel->category_id;
            $productInfo->product_group_id = $productModel->product_group_id;
            $productInfo->isAvailable      = $productModel->isAllowSale();

            // TODO 商品的服务

            // TODO 获取商户名称
        } catch (Throwable $throwable) {
            throw $throwable;

        }

        return $productInfo;

    }

    protected function getProduct(ProductIdentity $product) : Product
    {
        $query = FindQuery::from([]);
        $query->setKey($product->id);
        $query->include = ['skus'];
        return $this->productApplicationService->find($query);
    }

    public function getProductAmount(ProductPurchaseFactor $productPurchaseFactor) : ProductAmountInfo
    {
        $productAmount             = $this->productApplicationService->getProductPrice($productPurchaseFactor);
        $productAmount->quantity   = $productPurchaseFactor->quantity;
        $productAmount->totalPrice = $productAmount->price->multiply($productPurchaseFactor->quantity);
        // TODO
        //$productAmount->taxAmount     = $productAmount->totalPrice->multiply('0.06');
        //$productAmount->serviceAmount = $productAmount->totalPrice->multiply('0.1');

        return $productAmount;
    }


}