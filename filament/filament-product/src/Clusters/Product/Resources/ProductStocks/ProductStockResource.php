<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStocks;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStocks\Pages\ListProductStocks;
use RedJasmine\FilamentProduct\Clusters\Product\Stock\StockTableAction;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductStockResource extends Resource
{
    use ResourcePageHelper;

    /**
     * @var class-string<StockApplicationService::class>
     */
    protected static string $service = StockApplicationService::class;

    protected static ?string $model = ProductStock::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $cluster = Product::class;

    protected static ?int $navigationSort = 1;

    protected static bool $onlyOwner = true;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-stock.labels.product-stock');
    }


    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-stock.labels.product-stock');
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('id')
                          ->label(__('red-jasmine-product::product-stock.fields.id'))
                          ->copyable()
                          ->sortable(),

                // 商品信息
                TextColumn::make('product.title')
                          ->label(__('red-jasmine-product::product.fields.title'))
                          ->searchable()
                          ->sortable(),
                ImageColumn::make('product.image')
                          ->label(__('red-jasmine-product::product.fields.image'))
                          ->size(40)
                          ->circular(),

                // 变体信息
                TextColumn::make('variant_id')
                          ->label(__('red-jasmine-product::product-stock.fields.variant_id'))
                          ->copyable()
                          ->sortable(),
                TextColumn::make('variant.attrs_name')
                          ->label(__('red-jasmine-product::product.fields.attrs_name'))
                          ->searchable(),
                TextColumn::make('variant.sku')
                          ->label(__('red-jasmine-product::product.fields.sku'))
                          ->searchable(),
                TextColumn::make('variant.barcode')
                          ->label(__('red-jasmine-product::product.fields.barcode'))
                          ->searchable(),

                // 仓库信息
                TextColumn::make('warehouse_id')
                          ->label(__('red-jasmine-product::product-stock.fields.warehouse_id'))
                          ->formatStateUsing(fn($state) => $state == 0 ? '总仓' : $state)
                          ->sortable(),

                // 库存数量
                TextColumn::make('stock')
                          ->label(__('red-jasmine-product::product-stock.fields.stock'))
                          ->sortable()
                          ->numeric(),
                TextColumn::make('available_stock')
                          ->label(__('red-jasmine-product::product-stock.fields.available_stock'))
                          ->sortable()
                          ->numeric(),
                TextColumn::make('locked_stock')
                          ->label(__('red-jasmine-product::product-stock.fields.locked_stock'))
                          ->sortable()
                          ->numeric(),
                TextColumn::make('reserved_stock')
                          ->label(__('red-jasmine-product::product-stock.fields.reserved_stock'))
                          ->sortable()
                          ->numeric(),
                TextColumn::make('safety_stock')
                          ->label(__('red-jasmine-product::product-stock.fields.safety_stock'))
                          ->sortable()
                          ->numeric(),

                // 库存状态
                TextColumn::make('is_active')
                          ->label(__('red-jasmine-product::product-stock.fields.is_active'))
                          ->badge()
                          ->formatStateUsing(fn($state) => $state ? '启用' : '禁用')
                          ->color(fn($state) => $state ? 'success' : 'danger')
                          ->sortable(),
                TextColumn::make('priority')
                          ->label(__('red-jasmine-product::product-stock.fields.priority'))
                          ->sortable()
                          ->numeric(),

                // 所属者信息
                TextColumn::make('owner_type')
                          ->label(__('red-jasmine-product::product-stock.fields.owner_type'))
                          ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('owner_id')
                          ->label(__('red-jasmine-product::product-stock.fields.owner_id'))
                          ->toggleable(isToggledHiddenByDefault: true),

                // 时间信息
                TextColumn::make('created_at')
                          ->label(__('red-jasmine-product::product-stock.fields.created_at'))
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                          ->label(__('red-jasmine-product::product-stock.fields.updated_at'))
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                StockTableAction::make('stock-edit')
            ])
            ->recordUrl(null)
            ->defaultSort('id', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index' => ListProductStocks::route('/'),
        ];
    }

    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        $findQuery->include = ['product', 'variant'];
        return $findQuery;
    }
}
