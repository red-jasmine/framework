<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\ProductPriceResource;
use RedJasmine\Product\Domain\Price\Models\ProductPrice as Model;

class ProductPriceTable
{
    /**
     * 配置表格
     */
    public static function configure(Table $table) : Table
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
    protected static function getColumns() : array
    {
        return [
            TextColumn::make('id')
                      ->label(__('red-jasmine-product::product-price.fields.id'))
                      ->copyable()
                      ->sortable()
                      ->searchable()
                      ->icon('heroicon-o-identification')
                      ->color('gray')
                      ->size('xs'),

            TextColumn::make('product.title')
                      ->label(__('red-jasmine-product::product-price.fields.product'))
                      ->searchable()
                      ->limit(30)
                      ->tooltip(fn($record) => $record->product?->title)
                      ->weight('bold'),

            TextColumn::make('market')
                      ->label(__('red-jasmine-product::product-price.fields.market'))
                      ->badge()
                      ->color('primary')
                      ->searchable(),

            TextColumn::make('store')
                      ->label(__('red-jasmine-product::product-price.fields.store'))
                      ->badge()
                      ->color('success')
                      ->searchable()
                      ->toggleable(),

            TextColumn::make('user_level')
                      ->label(__('red-jasmine-product::product-price.fields.user_level'))
                      ->badge()
                      ->color('warning')
                      ->searchable(),
            TextColumn::make('quantity')
                      ->label(__('red-jasmine-product::product-price.fields.quantity'))
            ,

            TextColumn::make('currency')
                      ->label(__('red-jasmine-product::product-price.fields.currency'))
                      ->badge()
                      ->color('gray')
            ,

            TextColumn::make('price')
                      ->label(__('red-jasmine-product::product-price.fields.price'))
                      ->formatStateUsing(fn($state) => $state?->format())
                      ->color('danger')
                      ->weight('bold')
                      ->sortable(),

            TextColumn::make('market_price')
                      ->label(__('red-jasmine-product::product-price.fields.market_price'))
                      ->formatStateUsing(fn($state) => $state?->format())
                      ->color('success')
                      ->toggleable(),

            TextColumn::make('cost_price')
                      ->label(__('red-jasmine-product::product-price.fields.cost_price'))
                      ->formatStateUsing(fn($state) => $state?->format())
                      ->color('danger')
                      ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                      ->label(__('red-jasmine-product::product-price.fields.created_at'))
                      ->dateTime('Y-m-d H:i')
                      ->sortable()
                      ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                      ->label(__('red-jasmine-product::product-price.fields.updated_at'))
                      ->dateTime('Y-m-d H:i')
                      ->sortable()
                      ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * 获取筛选器
     */
    protected static function getFilters() : array
    {
        return [
            // SelectFilter::make('product_id')
            //     ->label(__('red-jasmine-product::product-price.fields.product'))
            //     ->relationship('product', 'title'),
            //
            // SelectFilter::make('variant_id')
            //     ->label(__('red-jasmine-product::product-price.fields.variant'))
            //     ->relationship('variant', 'attrs_name'),
            //
            // SelectFilter::make('market')
            //     ->label(__('red-jasmine-product::product-price.fields.market'))
            //     ->options([
            //         '*' => __('red-jasmine-product::product-price.market.all'),
            //         'cn' => __('red-jasmine-product::product-price.market.cn'),
            //         'us' => __('red-jasmine-product::product-price.market.us'),
            //         'de' => __('red-jasmine-product::product-price.market.de'),
            //     ]),
            //
            // SelectFilter::make('user_level')
            //     ->label(__('red-jasmine-product::product-price.fields.user_level'))
            //     ->options([
            //         '*' => __('red-jasmine-product::product-price.user_level.all'),
            //         'default' => __('red-jasmine-product::product-price.user_level.default'),
            //         'vip' => __('red-jasmine-product::product-price.user_level.vip'),
            //         'gold' => __('red-jasmine-product::product-price.user_level.gold'),
            //         'platinum' => __('red-jasmine-product::product-price.user_level.platinum'),
            //     ]),
        ];
    }

    /**
     * 获取记录操作
     */
    protected static function getRecordActions() : array
    {
        return [
            EditAction::make(),
            ActionGroup::make([
                DeleteAction::make(),
            ])
                       ->visible(static function (Model $record) : bool {
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
    protected static function getToolbarActions() : array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}

