<?php

namespace RedJasmine\Product\Domain\Price;

use RedJasmine\Product\Domain\Price\Data\ProductPriceData;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;
use RedJasmine\Support\Foundation\Service\Service;

class ProductPriceDomainService extends Service
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,

    ) {
    }

    /**
     * 获取商品价格
     *
     * @param  ProductPriceData  $data
     *
     * @return MoneyOld
     */
    public function getPrice(ProductPriceData $data) : MoneyOld
    {
        // 获取商品
        $product = $this->productRepository->find($data->productId);

        // 获取规格
        $sku = $product->skus->where('id', $data->skuId)->firstOrFail();


        // TODO 根据参数获取更多的价格处理

        return $sku->price;


    }


}
