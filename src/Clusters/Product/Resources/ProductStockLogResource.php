<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductStockLogResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductStockLogResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;

class ProductStockLogResource extends Resource
{
    protected static ?string $model = ProductStockLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $cluster = Product::class;

    protected static ?int $navigationSort = 2;



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

            ->defaultSort('id','desc')
            ->paginated([10, 25, 50, 100])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('red-jasmine-product::product-stock-log.fields.id'))
                                                     ->sortable(),
                Tables\Columns\TextColumn::make('owner_type')
                    ->label(__('red-jasmine-product::product-stock-log.fields.owner_type')),
                Tables\Columns\TextColumn::make('owner_id')
                    ->label(__('red-jasmine-product::product-stock-log.fields.owner_id'))
                ,
                Tables\Columns\TextColumn::make('product.title')
                    ->label(__('red-jasmine-product::product.fields.title')),
                Tables\Columns\TextColumn::make('sku.properties_name')
                ->label(__('red-jasmine-product::product.fields.properties_name')),
                Tables\Columns\TextColumn::make('action_type')->label(__('red-jasmine-product::product-stock-log.fields.action_type'))->enum(),
                Tables\Columns\TextColumn::make('action_stock')
                                         ->label(__('red-jasmine-product::product-stock-log.fields.action_stock'))
                                         ->numeric()
                ,

                Tables\Columns\TextColumn::make('change_type')
                    ->label(__('red-jasmine-product::product-stock-log.fields.change_type'))->enum()
                   ,
                Tables\Columns\TextColumn::make('change_detail')
                    ->label(__('red-jasmine-product::product-stock-log.fields.change_detail'))
                    ,

                Tables\Columns\TextColumn::make('lock_stock')
                    ->label(__('red-jasmine-product::product-stock-log.fields.lock_stock'))
                    ->numeric()
                   ,
                Tables\Columns\TextColumn::make('channel_type')->badge()

                    ->label(__('red-jasmine-product::product-stock-log.fields.channel_type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('channel_id')
                    ->label(__('red-jasmine-product::product-stock-log.fields.channel_id'))
                    ->numeric()
                    ,
                Tables\Columns\TextColumn::make('creator_type')
                    ->label(__('red-jasmine-product::product-stock-log.fields.creator_type'))
                   ,
                Tables\Columns\TextColumn::make('creator_id')
                    ->label(__('red-jasmine-product::product-stock-log.fields.creator_id'))
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('red-jasmine-product::product-stock-log.fields.created_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('red-jasmine-product::product-stock-log.fields.updated_at'))
                    ->dateTime()

                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
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
            'index' => \RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogResource\Pages\ListProductStockLogs::route('/')
        ];
    }
}
