<?php

namespace RedJasmine\Product\Domain\Price;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Foundation\Service\Service;

class ProductPriceDomainService extends Service
{
    // 影响价格因素
    // 数量、卖家

    // TODO 动态化获取
    public function getPrice(Product $product, int $skuID) : Amount
    {
        $sku = $product->skus->where('id', $skuID)->firstOrFail();
        // 获取价格

        return $sku->price;
    }

}
