<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;

class ProductStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return true;
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $owner = $user;
        if($user instanceof BelongsToOwnerInterface){
            $owner = $owner->owner();
        }


        // 获取商品总数
        $totalProducts = Product::query()
            ->where('owner_type',$owner->getType())
            ->where('owner_id', $owner->id)
            ->count();

        // 获取在售商品数量
        $availableProducts = Product::query()
            ->where('owner_type',$owner->getType())
            ->where('owner_id', $owner->id)
            ->where('status', ProductStatusEnum::AVAILABLE)
            ->count();

        // 获取草稿商品数量
        $draftProducts = Product::query()
            ->where('owner_type',$owner->getType())
            ->where('owner_id', $owner->id)
            ->where('status', ProductStatusEnum::DRAFT)
            ->count();

        // 获取库存预警商品数量
        $stockAlarmingProducts = ProductStock::query()
            ->where('owner_type',$owner->getType())
            ->where('owner_id', $owner->id)
            ->whereRaw('stock <= safety_stock')
            ->count();

        return [
            Stat::make('商品总数', $totalProducts)
                ->description('所有商品')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('在售商品', $availableProducts)
                ->description('正在销售中')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([3, 2, 4, 3, 4, 2, 3]),

            Stat::make('草稿商品', $draftProducts)
                ->description('待发布')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning')
                ->chart([2, 1, 3, 2, 3, 1, 2]),

            Stat::make('库存预警', $stockAlarmingProducts)
                ->description('库存不足')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->chart([1, 2, 1, 2, 1, 2, 1]),
        ];
    }
}

