<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\ProductPriceResource;

class CreateProductPrice extends CreateRecord
{
    protected static string $resource = ProductPriceResource::class;

    use ResourcePageHelper;

    /**
     * 转换表单数据为命令数据
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 转换 variants 数组为 ProductPriceVariantData 对象数组
        $variants = [];
        foreach ($data['variants'] ?? [] as $variant) {
            if (isset($variant['variant_id']) && isset($variant['price'])) {
                $variants[] = [
                    'variantId' => $variant['variant_id'],
                    'price' => $variant['price'],
                    'marketPrice' => $variant['market_price'] ?? null,
                    'costPrice' => $variant['cost_price'] ?? null,
                ];
            }
        }

        return [
            'productId' => $data['product_id'],
            'variants' => $variants,
            'market' => $data['market'],
            'store' => $data['store'],
            'userLevel' => $data['user_level'],
            'currency' => $data['currency'],
            'quantityTiers' => $data['quantity_tiers'] ?? null,
            'priority' => $data['priority'] ?? 0,
        ];
    }

}
