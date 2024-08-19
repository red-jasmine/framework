<?php

namespace RedJasmine\Product\Domain\Price;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Product\Domain\Price\Data\ProductPriceData;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Foundation\Service\Service;

class ProductPriceDomainService extends Service
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,

    ) {
    }

    /**
     * 获取商品价格
     * @param  ProductPriceData  $data
     *
     * @return Amount
     */
    public function getPrice(ProductPriceData $data) : Amount
    {
        // 获取商品
        $product = $this->productRepository->find($data->productId);

        // 获取规格
        $sku = $product->skus->where('id', $data->skuId)->firstOrFail();


        // TODO 根据参数获取更多的价格处理

        return $sku->price;


    }


}
