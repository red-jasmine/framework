<?php

namespace RedJasmine\ShoppingCart\Infrastructure\Services;

use RedJasmine\ShoppingCart\Domain\Contracts\ProductServiceInterface;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\PriceInfo;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;
use RedJasmine\ShoppingCart\Exceptions\ShoppingCartException;
use Cknow\Money\Money;

class ProductServiceIntegration implements ProductServiceInterface
{
    public function getProductInfo(CartProductIdentity $identity): ?array
    {
        // TODO: 调用商品服务获取商品信息
        // 这里应该通过HTTP客户端或RPC调用商品服务
        // 暂时返回模拟数据
        return [
            'id' => $identity->productId,
            'sku_id' => $identity->skuId,
            'title' => '示例商品',
            'status' => 'on_sale',
            'is_available' => true,
        ];
    }

    public function getProductPrice(CartProductIdentity $identity): ?PriceInfo
    {
        // TODO: 调用商品服务获取价格信息
        // 暂时返回模拟数据
        $priceInfo = new PriceInfo();
        $priceInfo->price = Money::CNY(10000); // 100.00元
        $priceInfo->originalPrice = Money::CNY(12000); // 120.00元
        $priceInfo->discountAmount = Money::CNY(2000); // 20.00元
        $priceInfo->promotionType = 'discount';
        $priceInfo->promotionId = 'promo_001';
        
        return $priceInfo;
    }

    public function isProductAvailable(CartProductIdentity $identity): bool
    {
        // TODO: 调用商品服务校验商品状态
        // 暂时返回true
        return true;
    }

    public function getSkuProperties(CartProductIdentity $identity): array
    {
        // TODO: 调用商品服务获取SKU属性
        // 暂时返回模拟数据
        return [
            [
                'name' => '颜色',
                'value' => '红色',
            ],
            [
                'name' => '尺寸',
                'value' => 'L',
            ],
        ];
    }
} 