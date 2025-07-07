<?php

namespace RedJasmine\Product\Domain\Price;

use Cknow\Money\Money;
use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\ProductAmount;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Product\Domain\Product\Repositories\ProductReadRepositoryInterface;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Foundation\Service\Service;

class ProductPriceDomainService extends Service
{
    public function __construct(
        protected ProductRepositoryInterface $repository,
        protected ProductReadRepositoryInterface $readRepository,

    ) {
    }

    /**
     * 获取商品价格信息
     * - 价格
     * - 市场价格
     * - 税率
     *
     * @param  ProductPurchaseFactor  $data
     *
     * @return ProductAmount
     */
    public function getProductAmount(ProductPurchaseFactor $data) : ProductAmount
    {
        $product       = $this->repository->find($data->product->id);
        $productAmount = new ProductAmount(new Currency($product->price_currency));

        // 获取商品

        $sku = $product->getSkuBySkuId($data->product->skuId);

        $productAmount->price       = $sku->price;
        $productAmount->marketPrice = $sku->market_price;

        $productAmount->setCostPrice($sku->cost_price ?? Money::parse(0));
        $productAmount->taxRate = $product->tax_rate;


        return $productAmount;


    }


}
