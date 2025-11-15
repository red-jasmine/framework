<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\ProductPriceResource;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Product\Domain\Product\Models\Product;

class EditProductPrice extends EditRecord
{
    protected static string $resource = ProductPriceResource::class;
    
    use ResourcePageHelper;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    /**
     * 填充表单数据
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // 获取当前商品价格汇总记录对应的商品的所有变体价格（相同维度）
        /** @var ProductPrice $price */
        $price = $this->record;
        
        // 获取该商品在该维度下的所有变体价格
        $allPrices = ProductVariantPrice::query()
            ->where('product_id', $price->product_id)
            ->byDimensions($price->market, $price->store, $price->user_level)
            ->where('currency', $price->currency->getCode())
            ->get()
            ->keyBy('variant_id');

        // 获取商品的所有变体
        $product = Product::find($price->product_id);
        $variants = [];
        
        foreach ($product->variants as $variant) {
            $existingPrice = $allPrices->get($variant->id);
            
            $variants[] = [
                'variant_id' => $variant->id,
                'attrs_name' => $variant->attrs_name,
                'price' => $existingPrice ? ($existingPrice->price?->getAmount() / 100) : ($variant->price?->getAmount() / 100 ?? null),
                'market_price' => $existingPrice ? ($existingPrice->market_price?->getAmount() / 100) : ($variant->market_price?->getAmount() / 100 ?? null),
                'cost_price' => $existingPrice ? ($existingPrice->cost_price?->getAmount() / 100) : ($variant->cost_price?->getAmount() / 100 ?? null),
            ];
        }

        return [
            'product_id' => $price->product_id,
            'market' => $price->market,
            'store' => $price->store,
            'user_level' => $price->user_level,
            'currency' => $price->currency->getCode(),
            'priority' => 0, // ProductPrice 没有 priority，从变体价格中获取
            'variants' => $variants,
        ];
    }

    /**
     * 转换表单数据为命令数据
     */
    protected function mutateFormDataBeforeSave(array $data): array
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

        // 编辑时也使用创建命令（批量创建处理器会自动处理更新）
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

    /**
     * 重写保存逻辑，使用创建命令进行批量更新
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $resource = static::getResource();
        $commandService = app($resource::getService());
        $command = ($resource::getCreateCommand())::from($data);
        
        // 批量创建/更新变体价格（会自动更新商品价格汇总）
        $commandService->create($command);
        
        // 返回更新后的商品价格汇总记录
        /** @var ProductPrice $record */
        return $record->refresh();
    }
}
