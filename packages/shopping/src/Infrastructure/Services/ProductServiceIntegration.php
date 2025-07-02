<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\ProductIdentity;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Data\ProductAmountData;
use RedJasmine\Shopping\Domain\Data\ProductInfo;
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
        $query->setKey($product->productId);
        $query->include = ['skus'];
        return $this->productApplicationService->find($query);
    }

    public function getProductInfo(ProductPurchaseFactor $productPurchaseFactor) : ProductInfo
    {
        $productInfo              = new ProductInfo();
        $productInfo->product     = $productPurchaseFactor->product;
        $productInfo->isAvailable = false;
        try {
            $productModel                = $this->getProduct($productPurchaseFactor->product);
            $sku                         = $productModel->getSkuBySkuId($productPurchaseFactor->product->skuId);
            $productInfo->title          = $productModel->title;
            $productInfo->image          = $productModel->image;
            $productInfo->propertiesName = $sku->properties_name;
            $productInfo->isAvailable    = $productModel->isAllowSale();
        } catch (Throwable $throwable) {

        }

        return $productInfo;

    }


    public function getProductAmount(ProductPurchaseFactor $productPurchaseFactor) : ProductAmountData
    {
        $price = $this->productApplicationService->getProductPrice($productPurchaseFactor);

        $productAmount           = new  ProductAmountData($price->getCurrency());
        $productAmount->quantity = $productPurchaseFactor->quantity;

        $productAmount->price      = $price;
        $productAmount->totalPrice = $productAmount->price->multiply($productPurchaseFactor->quantity);
        // TODO
        $productAmount->taxAmount     = $productAmount->totalPrice->multiply('0.06');
        $productAmount->serviceAmount = $productAmount->totalPrice->multiply('0.1');

        return $productAmount;
    }


}