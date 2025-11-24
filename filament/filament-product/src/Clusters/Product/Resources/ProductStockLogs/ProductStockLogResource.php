<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogs;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogs\Pages\ListProductStockLogs;
use RedJasmine\Product\Application\Stock\Services\StockLogQueryService;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductStockLogResource extends Resource
{
    use ResourcePageHelper;

    /**
     * @var class-string<StockLogQueryService::class>
     */
    protected static string $service = StockLogQueryService::class;

    protected static ?string $model = ProductStockLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $cluster = Product::class;

    protected static ?int $navigationSort = 2;

    protected static bool $onlyOwner = true;




    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-stock.labels.product-stock');
    }

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-stock-log.labels.product-stock-log');
    }


    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('id', 'desc')
            ->paginated([10, 25, 50, 100])
            ->columns([
                TextColumn::make('id')
                    ->label(__('red-jasmine-product::product-stock-log.fields.id'))
                    ->copyable()
                    ->sortable(),

                // 商品信息
                TextColumn::make('product.title')
                    ->label(__('red-jasmine-product::product.fields.title'))
                    ->searchable()
                    ->sortable(),

                // 变体信息
                TextColumn::make('variant_id')
                    ->label(__('red-jasmine-product::product-stock-log.fields.variant_id'))
                    ->copyable()
                    ->sortable(),
                TextColumn::make('sku.attrs_name')
                    ->label(__('red-jasmine-product::product.fields.attrs_name'))
                    ->searchable(),
                TextColumn::make('sku.sku')
                    ->label(__('red-jasmine-product::product.fields.sku'))
                    ->searchable(),

                // 仓库信息
                TextColumn::make('warehouse_id')
                    ->label(__('red-jasmine-product::product-stock-log.fields.warehouse_id'))
                    ->formatStateUsing(fn($state) => $state == 0 ? '总仓' : $state)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 操作信息
                TextColumn::make('action_type')
                    ->label(__('red-jasmine-product::product-stock-log.fields.action_type'))
                    ->badge()
                    ->useEnum()
                    ->sortable(),

                // 库存变化
                TextColumn::make('before_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.before_stock'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('action_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.action_stock'))
                    ->numeric()
                    ->color(fn($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                    ->sortable(),
                TextColumn::make('after_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.after_stock'))
                    ->numeric()
                    ->sortable(),

                // 可用库存变化
                TextColumn::make('before_available_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.before_available_stock'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('after_available_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.after_available_stock'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 锁定库存变化
                TextColumn::make('before_locked_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.before_locked_stock'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('after_locked_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.after_locked_stock'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 预留库存变化
                TextColumn::make('before_reserved_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.before_reserved_stock'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('after_reserved_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.after_reserved_stock'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 业务信息
                TextColumn::make('business_type')
                    ->label(__('red-jasmine-product::product-stock-log.fields.business_type'))
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('business_no')
                    ->label(__('red-jasmine-product::product-stock-log.fields.business_no'))
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('business_detail')
                    ->label(__('red-jasmine-product::product-stock-log.fields.business_detail'))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                // 所属者信息
                TextColumn::make('owner_type')
                    ->label(__('red-jasmine-product::product-stock-log.fields.owner_type'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('owner_id')
                    ->label(__('red-jasmine-product::product-stock-log.fields.owner_id'))
                    ->toggleable(isToggledHiddenByDefault: true),

                // 操作者信息
                TextColumn::make('creator_nickname')
                    ->label(__('red-jasmine-product::product-stock-log.fields.creator_nickname'))
                    ->toggleable(isToggledHiddenByDefault: true),

                // 时间信息
                TextColumn::make('created_at')
                    ->label(__('red-jasmine-product::product-stock-log.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductStockLogs::route('/')
        ];
    }

    public static function callFindQuery(FindQuery $findQuery): FindQuery
    {
        $findQuery->include = ['product', 'sku'];
        return $findQuery;
    }
}
