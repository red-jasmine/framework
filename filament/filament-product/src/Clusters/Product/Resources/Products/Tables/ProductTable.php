<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\ProductResource;
use RedJasmine\FilamentProduct\Clusters\Product\Stock\StockTableAction;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product as Model;

class ProductTable
{
    /**
     * 配置表格
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns(static::getColumns())
            ->filters(static::getFilters(), layout: FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->recordUrl(null)
            ->recordActions(static::getRecordActions())
            ->toolbarActions(static::getToolbarActions());
    }

    /**
     * 获取表格列
     */
    protected static function getColumns(): array
    {
        return [
            ...ProductResource::ownerTableColumns(),

            // 商品信息组
            ImageColumn::make('image')
                ->label(__('red-jasmine-product::product.fields.image'))
                ->circular()
                ->size(60)
                ->defaultImageUrl(url('/images/placeholder.png')),

            TextColumn::make('id')
                ->label(__('red-jasmine-product::product.fields.id'))
                ->copyable()
                ->sortable()
                ->searchable()
                ->icon('heroicon-o-identification')
                ->color('gray')
                ->size('xs'),

            TextColumn::make('title')
                ->label(__('red-jasmine-product::product.fields.title'))
                ->copyable()
                ->searchable()
                ->limit(30)
                ->tooltip(fn($record) => $record->title)
                ->weight('bold')
                ->description(fn($record) => $record->slogan),

            // 分类品牌
            TextColumn::make('category.name')
                ->label(__('red-jasmine-product::product.fields.category_id'))
                ->badge()
                ->color('info')
                ->icon('heroicon-o-tag')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('brand.name')
                ->label(__('red-jasmine-product::product.fields.brand_id'))
                ->badge()
                ->color('warning')
                ->icon('heroicon-o-star')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('productGroup.name')
                ->label(__('red-jasmine-product::product.fields.product_group_id'))
                ->badge()
                ->color('primary')
                ->toggleable(isToggledHiddenByDefault: true),

            // 商品类型
            TextColumn::make('product_type')
                ->label(__('red-jasmine-product::product.fields.product_type'))
                ->badge()
                ->useEnum(),

            // 规格信息
            IconColumn::make('has_variants')
                ->label(__('red-jasmine-product::product.fields.has_variants'))
                ->boolean()
                ->trueIcon('heroicon-o-squares-2x2')
                ->falseIcon('heroicon-o-square-2-stack')
                ->trueColor('success')
                ->falseColor('gray')
                ->toggleable(isToggledHiddenByDefault: true),

            // 价格信息
            TextColumn::make('price')
                ->label(__('red-jasmine-product::product.fields.price'))
                ->formatStateUsing(fn($state) => $state?->format())
                ->color('danger')
                ->weight('bold')
                ->sortable(),

            TextColumn::make('market_price')
                ->label(__('red-jasmine-product::product.fields.market_price'))
                ->formatStateUsing(fn($state) => $state?->format())
                ->color('success')
                ->weight('bold')
                ->sortable()
                ->toggleable(true, true),

            TextColumn::make('cost_price')
                ->label(__('red-jasmine-product::product.fields.cost_price'))
                ->formatStateUsing(fn($state) => $state?->format())
                ->color('danger')
                ->weight('bold')
                ->sortable()
                ->toggleable(true, true),

            // 库存信息
            TextColumn::make('stock')
                ->label(__('red-jasmine-product::product.fields.stock'))
                ->numeric()
                ->sortable()
                ->icon('heroicon-o-archive-box')
                ->color(fn($state, $record) => match (true) {
                    $state <= 0 => 'danger',
                    $state <= $record->safety_stock => 'warning',
                    default => 'success'
                })
                ->badge()
                ->description(fn($record) => $record->safety_stock > 0 ? "安全库存: {$record->safety_stock}" : null),

            // 状态
            TextColumn::make('status')
                ->label(__('red-jasmine-product::product.fields.status'))
                ->badge()
                ->useEnum(),

            // 销售数据
            TextColumn::make('sales')
                ->label(__('red-jasmine-product::product.fields.sales'))
                ->numeric()
                ->sortable()
                ->icon('heroicon-o-chart-bar')
                ->color('success')
                ->toggleable(),

            TextColumn::make('views')
                ->label(__('red-jasmine-product::product.fields.views'))
                ->numeric()
                ->sortable()
                ->icon('heroicon-o-eye')
                ->color('info')
                ->toggleable(),

            TextColumn::make('spu')
                ->label(__('red-jasmine-product::product.fields.spu'))
                ->searchable()
                ->copyable()
                ->icon('heroicon-o-hashtag')
                ->toggleable(true, true),

            // 时间信息
            TextColumn::make('available_at')
                ->sortable()
                ->label(__('red-jasmine-product::product.fields.available_at'))
                ->dateTime('Y-m-d H:i')
                ->icon('heroicon-o-calendar')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('modified_at')
                ->sortable()
                ->label(__('red-jasmine-product::product.fields.modified_time'))
                ->dateTime('Y-m-d H:i')
                ->since()
                ->icon('heroicon-o-clock')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('sort')
                ->label(__('red-jasmine-product::product.fields.sort'))
                ->numeric()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('safety_stock')
                ->label(__('red-jasmine-product::product.fields.safety_stock'))
                ->numeric()
                ->toggleable(isToggledHiddenByDefault: true),


        ];
    }

    /**
     * 获取筛选器
     */
    protected static function getFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->multiple()
                ->label(__('red-jasmine-product::product.fields.status'))
                ->options(ProductStatusEnum::options()),

            SelectFilter::make('product_type')
                ->multiple()
                ->label(__('red-jasmine-product::product.fields.product_type'))
                ->options(ProductTypeEnum::options()),
        ];
    }

    /**
     * 获取记录操作
     */
    protected static function getRecordActions(): array
    {
        return [
            EditAction::make(),
            ActionGroup::make([
                ViewAction::make(),
                StockTableAction::make('stock-edit'),
                DeleteAction::make(),
                Action::make('listing-removal')
                    ->label(function (Model $record) {
                        return $record->status !== ProductStatusEnum::AVAILABLE ?
                            __('red-jasmine-product::product.commands.listing')
                            :
                            __('red-jasmine-product::product.commands.removal');
                    })
                    ->successNotificationTitle('ok')
                    ->icon(function (Model $record) {
                        return $record->status !== ProductStatusEnum::AVAILABLE ?
                            FilamentIcon::resolve('product.commands.listing') ?? 'heroicon-o-arrow-up-circle'
                            :
                            FilamentIcon::resolve('product.commands.removal') ?? 'heroicon-o-arrow-down-circle';
                    })
                    ->action(function (Model $record, Action $action) {
                        $status = ($record->status === ProductStatusEnum::AVAILABLE) ? ProductStatusEnum::UNAVAILABLE : ProductStatusEnum::AVAILABLE;
                        $command = ProductSetStatusCommand::from(['id' => $record->id, 'status' => $status]);
                        $service = app(ProductApplicationService::class);

                        $service->setStatus($command);
                        $action->success();
                    }),
            ])
                ->visible(static function (Model $record): bool {
                    if (method_exists($record, 'trashed')) {
                        return !$record->trashed();
                    }
                    return true;
                }),

            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
    }

    /**
     * 获取工具栏操作
     */
    protected static function getToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
