<?php

namespace RedJasmine\Product\Domain\Price;

use Cknow\Money\Money;
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
     * 获取商品价格
     *
     * @param  ProductPurchaseFactor  $data
     *
     * @return Money
     */
    public function getPrice(ProductPurchaseFactor $data) : Money
    {

        // 获取商品
        $product = $this->repository->find($data->product->productId);
        $sku     = $product->getSkuBySkuId($data->product->skuId);

        // TODO 根据参数获取更多的价格处理

        return $sku->price;


    }


}
