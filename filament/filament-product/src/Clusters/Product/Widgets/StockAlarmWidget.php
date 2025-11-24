<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;

class StockAlarmWidget extends BaseWidget
{
    protected static ?string $heading = '库存预警商品';

    protected static ?int $sort = 4;

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
                ProductStock::query()
                    ->where('owner_type',$owner->getType())
                    ->where('owner_id', $owner->getId())
                    ->whereRaw('stock <= safety_stock')

                    ->orderBy('stock', 'asc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('商品名称')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('stock')
                    ->label('当前库存')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => $record->stock <= 0 ? 'danger' : 'warning'),

                Tables\Columns\TextColumn::make('safety_stock')
                    ->label('安全库存')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn ($state) => ProductStatusEnum::colors()[$state->value] ?? 'gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('stock', 'asc')
            ->emptyStateHeading('暂无库存预警商品')
            ->emptyStateDescription('所有商品库存充足');
    }
}

