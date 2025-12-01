<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourceOwnerHelper;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\Schemas\Operators;
use RedJasmine\FilamentCore\Resources\Schemas\Owner;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\Pages\CreateProductSeries;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\Pages\EditProductSeries;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\Pages\ListProductSeries;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\Pages\ViewProductSeries;
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

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $cluster = Product::class;

    protected static ?int $navigationSort = 5;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-series.labels.product-series');
    }

    protected static bool $onlyOwner = true;


    protected static ?string $service = ProductSeriesApplicationService::class;


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

        return $model;
    }


    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->columns(1)
            ->components([

                Owner::make(),
                TextInput::make('name')
                         ->label(__('red-jasmine-product::product-series.fields.name'))
                         ->required()
                         ->maxLength(255),
                TextInput::make('remarks')
                         ->label(__('red-jasmine-product::product-series.fields.remarks'))
                         ->maxLength(255),

                Repeater::make('products')
                        ->relationship('products')
                        ->dehydrated()
                        ->saveRelationshipsUsing(null)
                        ->compact()
                        ->table([
                            Repeater\TableColumn::make(__('red-jasmine-product::product-series.fields.product.product_id')),
                            Repeater\TableColumn::make(__('red-jasmine-product::product-series.fields.product.position')),
                        ])
                        ->label(__('red-jasmine-product::product-series.fields.products'))
                        ->schema(
                            [
                                Select::make('product_id')
                                      ->distinct()
                                      ->label(__('red-jasmine-product::product-series.fields.product.product_id'))
                                      ->searchable()
                                      ->inlineLabel()
                                      ->options(fn(Get $get
                                      ) => app(static::$productQueryService)->repository->query()->where('owner_type',
                                          $get('../../owner_type'))
                                                                                        ->where('owner_id',
                                                                                            (int) $get('../../owner_id'))->select([
                                              'id', 'title'
                                          ])->limit(10)->pluck('title', 'id')->toArray())
                                      ->getSearchResultsUsing(
                                          fn(Get $get
                                          ) => app(static::$productQueryService)->repository->query()->where('owner_type',
                                              $get('../../owner_type'))
                                                                                            ->where('owner_id',
                                                                                                (int) $get('../../owner_id'))->select([
                                                  'id', 'title'
                                              ])->limit(10)->pluck('title', 'id')->toArray())
                                      ->getOptionLabelUsing(
                                          fn(
                                              Get $get,
                                              $value
                                          ) => app(static::$productQueryService)->repository->query()->where('owner_type',
                                              $get('../../owner_type'))
                                                                                            ->where('owner_id',
                                                                                                (int) $get('../../owner_id'))
                                                                                            ->where('id',
                                                                                                $value)->first()?->title

                                      )
                                      ->required(),
                                TextInput::make('position')
                                         ->label(__('red-jasmine-product::product-series.fields.product.position'))
                                         ->required()
                                         ->integer()
                                         ->default(0)
                                         ->inlineLabel()
                                         ->maxLength(10)
                            ]

                        )
                        ->reorderable(false)
                        ->columns(2)
                        ->grid(2)
                        ->columnSpanFull()
                ,

                Operators::make(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->withCount('products'))
            ->columns([
                TextColumn::make('id')
                          ->label('ID')
                          ->sortable(),
                ... static::ownerTableColumns(),
                TextColumn::make('name')
                          ->label(__('red-jasmine-product::product-series.fields.name'))
                          ->searchable(),
                TextColumn::make('products_count')
                          ->label('数量')
                ,
                TextColumn::make('remarks')
                          ->label(__('red-jasmine-product::product-series.fields.remarks'))
                          ->searchable(),


            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index'  => ListProductSeries::route('/'),
            'create' => CreateProductSeries::route('/create'),
            'view'   => ViewProductSeries::route('/{record}'),
            'edit'   => EditProductSeries::route('/{record}/edit'),
        ];
    }
}
