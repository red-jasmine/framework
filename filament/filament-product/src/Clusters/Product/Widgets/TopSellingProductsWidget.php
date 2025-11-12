<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;

class TopSellingProductsWidget extends BaseWidget
{
    protected static ?string $heading = '热销商品排行';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return true;
    }

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $owner = auth()->user();
        if($owner instanceof BelongsToOwnerInterface){
            $owner = $owner->owner();
        }

        return $table
            ->query(
                Product::query()
                    ->where('owner_type',$owner->getType())
                    ->where('owner_id', $owner->getId())
                    ->where('status', ProductStatusEnum::AVAILABLE)
                    ->where('sales', '>', 0)
                    ->orderBy('sales', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('商品名称')
                    ->searchable()
                    ->limit(30)
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('sales')
                    ->label('销售量')
                    ->numeric()
                    ->sortable()
                    ->color('success')
                    ->icon('heroicon-o-shopping-cart'),

                Tables\Columns\TextColumn::make('price')
                    ->label('价格')
                    ->formatStateUsing(fn($state)=>$state?->format())
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('库存')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => $record->stock <= 0 ? 'danger' : null),

                Tables\Columns\TextColumn::make('views')
                    ->label('浏览量')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-eye'),

                Tables\Columns\TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->useEnum(),

                Tables\Columns\TextColumn::make('available_at')
                    ->label('上架时间')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('sales', 'desc')
            ->paginated(false)
            ->emptyStateHeading('暂无热销商品')
            ->emptyStateDescription('商品销售后才会显示在排行榜中');
    }
}

