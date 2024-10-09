<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\Pages;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\RelationManagers;
use RedJasmine\Product\Application\Series\Services\ProductSeriesCommandService;
use RedJasmine\Product\Application\Series\Services\ProductSeriesQueryService;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesDeleteCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductSeriesResource extends Resource
{
    protected static ?string $model = ProductSeries::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Product::class;

    use ResourcePageHelper;

    protected static ?string $commandService = ProductSeriesCommandService::class;
    protected static ?string $queryService   = ProductSeriesQueryService::class;
    protected static ?string $createCommand  = ProductSeriesCreateCommand::class;
    protected static ?string $updateCommand  = ProductSeriesUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductSeriesDeleteCommand::class;


    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        $findQuery->include = [ 'products' ];
        return $findQuery;
    }

    public static function callResolveRecord(Model $model) : Model
    {
        $model->products = $model->products->toArray();
        return $model;
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         Forms\Components\TextInput::make('owner_type')
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('owner_id')
                                                   ->required()
                                                   ->numeric(),
                         Forms\Components\TextInput::make('name')
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('remarks')
                                                   ->maxLength(255),


                         Forms\Components\Repeater::make('products')
                                                  ->relationship()
                                                  ->dehydrated(true)
                                                // TODO
                                                  ->saveRelationshipsUsing(function (){})
                                                  ->schema(
                                                      [
                                                          Forms\Components\Select::make('product_id')->relationship('product', 'title')->required(),
                                                          Forms\Components\TextInput::make('name')->required()->maxLength(10)
                                                      ]

                                                  ),

                         Forms\Components\TextInput::make('creator_type')->label(__('red-jasmine-product::product-property-value.fields.creator_type'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('creator_id')->label(__('red-jasmine-product::product-property-value.fields.creator_id'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_type')->label(__('red-jasmine-product::product-property-value.fields.updater_type'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_id')->label(__('red-jasmine-product::product-property-value.fields.updater_id'))->readOnly()->visibleOn('view'),
                     ])

            ;
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label('ID')
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('owner_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('owner_id')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('name')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('remarks')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('creator_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('creator_id')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('updater_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('updater_id')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('created_at')
                                                   ->dateTime()
                                                   ->sortable()
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updated_at')
                                                   ->dateTime()
                                                   ->sortable()
                                                   ->toggleable(isToggledHiddenByDefault: true),
                      ])
            ->filters([
                          //
                      ])
            ->actions([
                          Tables\Actions\ViewAction::make(),
                          Tables\Actions\EditAction::make(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                   ]),
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
            'index'  => Pages\ListProductSeries::route('/'),
            'create' => Pages\CreateProductSeries::route('/create'),
            'view'   => Pages\ViewProductSeries::route('/{record}'),
            'edit'   => Pages\EditProductSeries::route('/{record}/edit'),
        ];
    }
}
