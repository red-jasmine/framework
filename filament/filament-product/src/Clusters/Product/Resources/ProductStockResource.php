<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use RedJasmine\FilamentProduct\Clusters\Product\Stock\StockTableAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockResource\Pages\ListProductStocks;
use App\Filament\Clusters\Product\Resources\ProductStockResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductStockResource\RelationManagers;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;

class ProductStockResource extends Resource
{
    protected static ?string $model = \RedJasmine\Product\Domain\Stock\Models\Product::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $cluster = Product::class;

    protected static ?int $navigationSort = 1;


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
                          TextColumn::make('title')->label(__('red-jasmine-product::product.fields.title')),
                          ImageColumn::make('image')->label(__('red-jasmine-product::product.fields.image'))->size(40),
                          TextColumn::make('owner_type')
                                                   ->label(__('red-jasmine-product::product-stock.fields.owner_type'))
                          ,
                          TextColumn::make('owner_id')->label(__('red-jasmine-product::product-stock.fields.owner_id')),
                          TextColumn::make('title')->label(__('red-jasmine-product::product.fields.title')),
                          ImageColumn::make('image')->label(__('red-jasmine-product::product.fields.image'))->size(40),
                          TextColumn::make('outer_id')->label(__('red-jasmine-product::product.fields.outer_id')),

                          TextColumn::make('status')->label(__('red-jasmine-product::product.fields.status'))->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          TextColumn::make('stock')->label(__('red-jasmine-product::product-stock.fields.stock')),
                          TextColumn::make('lock_stock')->label(__('red-jasmine-product::product-stock.fields.lock_stock')),

                      ])
            ->filters([
                          //
                      ])
            ->recordActions([
                          StockTableAction::make('stock-edit')
                      ])
            ->recordUrl(null)
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
}
