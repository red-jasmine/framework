<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\ShoppingCart\Domain\Contracts\ProductServiceInterface;
use RedJasmine\ShoppingCart\Domain\Data\ProductInfo;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProduct;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\PriceInfo;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductServiceIntegration implements ProductServiceInterface
{
    public function __construct(
        protected ProductApplicationService $productApplicationService
    ) {
    }


    protected function getProduct(CartProduct $product) : Product
    {
        $query = FindQuery::from([]);
        $query->setKey($product->productId);
        $query->include = ['skus'];
        return $this->productApplicationService->find($query);
    }

    public function getProductInfo(CartProduct $product) : ?ProductInfo
    {
        $productModel                = $this->getProduct($product);
        $sku                         = $productModel->getSkuBySkuId($product->skuId);
        $productInfo                 = new ProductInfo();
        $productInfo->product        = $product;
        $productInfo->title          = $productModel->title;
        $productInfo->image          = $productModel->image;
        $productInfo->propertiesName = $sku->properties_name;
        $productInfo->isAvailable    = $productModel->isAllowSale();

        $productInfo->price       = $sku->price;
        $productInfo->marketPrice = $sku->marketPrice;

        return $productInfo;

    }

    public function isProductAvailable(CartProduct $product) : bool
    {
        // TODO: Implement isProductAvailable() method.
    }


    public function getProductPrice(CartProduct $product) : ?PriceInfo
    {
        // TODO: Implement getProductPrice() method.
    }


    public function getSkuProperties(CartProduct $product) : array
    {
        // TODO: Implement getSkuProperties() method.
    }


}