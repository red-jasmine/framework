<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductStockResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductStockResource\RelationManagers;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;

class ProductStockResource extends Resource
{
    protected static ?string $model = \RedJasmine\Product\Domain\Stock\Models\Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

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
                          Tables\Columns\TextColumn::make('id')
                                                   ->label(__('red-jasmine-product::product-stock.fields.id'))
                                                   ->copyable()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('title')->label(__('red-jasmine-product::product.fields.title')),
                          Tables\Columns\ImageColumn::make('image')->label(__('red-jasmine-product::product.fields.image'))->size(40),
                          Tables\Columns\TextColumn::make('owner_type')
                                                   ->label(__('red-jasmine-product::product-stock.fields.owner_type'))
                          ,
                          Tables\Columns\TextColumn::make('owner_id')->label(__('red-jasmine-product::product-stock.fields.owner_id')),
                          Tables\Columns\TextColumn::make('title')->label(__('red-jasmine-product::product.fields.title')),
                          Tables\Columns\ImageColumn::make('image')->label(__('red-jasmine-product::product.fields.image'))->size(40),
                          Tables\Columns\TextColumn::make('outer_id')->label(__('red-jasmine-product::product.fields.outer_id')),

                          Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product.fields.status'))->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('stock')->label(__('red-jasmine-product::product-stock.fields.stock')),
                          Tables\Columns\TextColumn::make('lock_stock')->label(__('red-jasmine-product::product-stock.fields.lock_stock')),

                      ])
            ->filters([
                          //
                      ])
            ->actions([
                          Product\Stock\StockTableAction::make('stock-edit')
                      ])
            ->recordUrl(null)
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                   ]),
                          ]);
    }


    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
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
            'index' => Product\Resources\ProductStockResource\Pages\ListProductStocks::route('/'),
        ];
    }
}
