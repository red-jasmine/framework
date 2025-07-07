<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\ProductAmount;
use RedJasmine\Ecommerce\Domain\Data\ProductIdentity;
use RedJasmine\Ecommerce\Domain\Data\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Throwable;

class ProductServiceIntegration implements ProductServiceInterface
{
    public function __construct(
        protected ProductApplicationService $productApplicationService,
    ) {
    }


    protected function getProduct(ProductIdentity $product) : Product
    {
        $query = FindQuery::from([]);
        $query->setKey($product->id);
        $query->include = ['skus'];
        return $this->productApplicationService->find($query);
    }

    public function getProductInfo(ProductPurchaseFactor $productPurchaseFactor) : ProductInfo
    {
        $productInfo              = new ProductInfo();
        $productInfo->product     = $productPurchaseFactor->product;
        $productInfo->isAvailable = false;
        try {
            $productModel                 = $this->getProduct($productPurchaseFactor->product);
            $sku                          = $productModel->getSkuBySkuId($productPurchaseFactor->product->skuId);
            $productInfo->product->seller = $productModel->owner;
            $productInfo->title           = $productModel->title;
            $productInfo->image           = $productModel->image;
            $productInfo->maxLimit        = $productModel->max_limit;
            $productInfo->minLimit        = $productModel->min_limit;
            $productInfo->stepLimit       = $productModel->step_limit;
            $productInfo->propertiesName  = $sku->properties_name;
            // 获取可选的发货类型
            $productInfo->productType   = $productModel->product_type;
            $productInfo->shippingTypes = $productModel->getAllowShippingTypes();

            $productInfo->isAvailable = $productModel->isAllowSale();

            // TODO 商品的服务

            // TODO 获取商户名称
        } catch (Throwable $throwable) {

        }

        return $productInfo;

    }


    public function getProductAmount(ProductPurchaseFactor $productPurchaseFactor) : ProductAmount
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