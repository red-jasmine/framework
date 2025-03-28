<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourceOwnerHelper;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\Pages;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\RelationManagers;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Application\Series\Services\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Application\Series\Services\Commands\ProductSeriesDeleteCommand;
use RedJasmine\Product\Application\Series\Services\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Application\Series\Services\ProductSeriesApplicationService;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductSeriesResource extends Resource
{


    use ResourcePageHelper;

    protected static ?string $model = ProductSeries::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $cluster = Product::class;

    protected static ?int $navigationSort = 5;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-series.labels.product-series');
    }

    protected static bool $onlyOwner = true;


    protected static ?string $service        = ProductSeriesApplicationService::class;
    protected static ?string $commandService = ProductSeriesApplicationService::class;

    protected static ?string $productQueryService = ProductApplicationService::class;
    protected static ?string $createCommand       = ProductSeriesCreateCommand::class;
    protected static ?string $updateCommand       = ProductSeriesUpdateCommand::class;
    protected static ?string $deleteCommand       = ProductSeriesDeleteCommand::class;


    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        $findQuery->include = ['products'];
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
                ...static::ownerFormSchemas(),
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine-product::product-series.fields.name'))
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('remarks')
                                          ->label(__('red-jasmine-product::product-series.fields.remarks'))
                                          ->maxLength(255),


                Forms\Components\Repeater::make('products')
                                         ->label(__('red-jasmine-product::product-series.fields.products'))
                                         ->schema(
                                             [
                                                 Forms\Components\Select::make('product_id')
                                                                        ->label(__('red-jasmine-product::product-series.fields.product.product_id'))
                                                                        ->searchable()
                                                                        ->inlineLabel()
                                                                        ->options(fn(Forms\Get $get
                                                                        ) => app(static::$productQueryService)->readRepository->modelQuery()->where('owner_type',
                                                                            $get('../../owner_type'))
                                                                                                              ->where('owner_id',
                                                                                                                  (int) $get('../../owner_id'))->select([
                                                                                'id', 'title'
                                                                            ])->limit(10)->pluck('title', 'id')->toArray())
                                                                        ->getSearchResultsUsing(
                                                                            fn(Forms\Get $get
                                                                            ) => app(static::$productQueryService)->readRepository->modelQuery()->where('owner_type',
                                                                                $get('../../owner_type'))
                                                                                                                  ->where('owner_id',
                                                                                                                      (int) $get('../../owner_id'))->select([
                                                                                    'id', 'title'
                                                                                ])->limit(10)->pluck('title', 'id')->toArray())
                                                                        ->getOptionLabelUsing(
                                                                            fn(
                                                                                Forms\Get $get,
                                                                                $value
                                                                            ) => app(static::$productQueryService)->readRepository->modelQuery()->where('owner_type',
                                                                                $get('../../owner_type'))
                                                                                                                  ->where('owner_id',
                                                                                                                      (int) $get('../../owner_id'))
                                                                                                                  ->where('id',
                                                                                                                      $value)->first()?->title

                                                                        )
                                                                        ->required(),
                                                 Forms\Components\TextInput::make('name')
                                                                           ->label(__('red-jasmine-product::product-series.fields.product.name'))
                                                                           ->required()
                                                                           ->inlineLabel()
                                                                           ->maxLength(10)
                                             ]

                                         )
                                         ->reorderable(false)
                                         ->columns(2)
                                         ->grid(2)
                                         ->columnSpanFull()
                ,

                ... static::operateFormSchemas()
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->withCount('products'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label('ID')
                                         ->sortable(),
                ... static::ownerTableColumns(),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-product::product-series.fields.name'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('products_count')
                                         ->label('数量')
                ,
                Tables\Columns\TextColumn::make('remarks')
                                         ->label(__('red-jasmine-product::product-series.fields.remarks'))
                                         ->searchable(),

                ... static::operateTableColumns()

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
