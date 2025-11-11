<?php

namespace RedJasmine\Product\Domain\Product\Services;

use Illuminate\Support\Collection;
use RedJasmine\Product\Domain\Product\Data\Product;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 商品领域服务
 * 负责商品领域的核心业务规则验证
 */
class ProductDomainService extends Service
{
    /**
     * 验证商品属性规则
     * 验证销售属性和基础属性不能有重复的属性项目
     *
     * @param  Product  $productData
     *
     * @return void
     * @throws ProductException
     */
    public function validateAttributes(Product $productData): void
    {
        if (!$productData->saleAttrs || !$productData->basicAttrs) {
            return;
        }

        $saleAttrIds = $productData->saleAttrs->pluck('aid');
        $basicAttrIds = $productData->basicAttrs->pluck('aid');

        if ($saleAttrIds->intersect($basicAttrIds)->isNotEmpty()) {
            throw new ProductException('属性不能重复');
        }
    }
}

