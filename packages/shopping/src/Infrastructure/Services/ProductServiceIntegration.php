<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactors;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\ProductIdentity;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Data\ProductInfo;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

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

    public function getProductInfo(ProductPurchaseFactors $productPurchaseFactors) : ProductInfo
    {
        $productInfo              = new ProductInfo();
        $productInfo->product     = $productPurchaseFactors->product;
        $productInfo->isAvailable = false;
        try {
            $productModel                = $this->getProduct($productPurchaseFactors->product);
            $sku                         = $productModel->getSkuBySkuId($productPurchaseFactors->product->skuId);
            $productInfo->title          = $productModel->title;
            $productInfo->image          = $productModel->image;
            $productInfo->propertiesName = $sku->properties_name;
            $productInfo->isAvailable    = $productModel->isAllowSale();
        } catch (\Throwable $throwable) {

        }

        return $productInfo;

    }


    public function getProductPrice(ProductPurchaseFactors $productPurchaseFactors) : ?Money
    {
        return $this->productApplicationService->getProductPrice($productPurchaseFactors);
    }


}