<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\Schemas\Operators;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages\CreateProductService;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages\EditProductService;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages\ListProductServices;
use RedJasmine\Product\Application\Service\Services\Commands\ProductServiceCreateCommand;
use RedJasmine\Product\Application\Service\Services\Commands\ProductServiceDeleteCommand;
use RedJasmine\Product\Application\Service\Services\Commands\ProductServiceUpdateCommand;
use RedJasmine\Product\Application\Service\Services\ProductServiceApplicationService;
use RedJasmine\Product\Domain\Service\Models\Enums\ServiceStatusEnum;
use RedJasmine\Product\Domain\Service\Models\ProductService;

class ProductServiceResource extends Resource
{
    protected static ?string $model = ProductService::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $cluster = Product::class;


    protected static ?int $navigationSort = 6;

    use ResourcePageHelper;

    protected static ?string $service       = ProductServiceApplicationService::class;
    protected static ?string $createCommand = ProductServiceCreateCommand::class;
    protected static ?string $updateCommand = ProductServiceUpdateCommand::class;
    protected static ?string $deleteCommand = ProductServiceDeleteCommand::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-service.labels.service');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product.labels.brand-category-service');
    }

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                         ->label(__('red-jasmine-product::product-service.fields.name'))
                         ->required()
                         ->maxLength(255),
                TextInput::make('description')
                         ->label(__('red-jasmine-product::product-service.fields.description'))
                         ->maxLength(255),
                TextInput::make('icon')
                         ->label(__('red-jasmine-product::product-service.fields.icon'))
                         ->maxLength(255),
                ColorPicker::make('color')
                           ->label(__('red-jasmine-product::product-service.fields.color'))
                ,
                TextInput::make('cluster')
                         ->label(__('red-jasmine-product::product-service.fields.cluster'))
                         ->maxLength(255),
                TextInput::make('sort')
                         ->label(__('red-jasmine-product::product-service.fields.sort'))
                         ->required()
                         ->numeric()
                         ->default(0),
                Radio::make('is_show')
                     ->label(__('red-jasmine-product::product-service.fields.is_show'))
                     ->required()
                     ->boolean()->inline()
                     ->default(1),
                ToggleButtons::make('status')
                             ->label(__('red-jasmine-product::product-service.fields.status'))
                             ->required()
                             ->grouped()
                             ->default(32)
                             ->default(ServiceStatusEnum::ENABLE)
                             ->useEnum(ServiceStatusEnum::class),
                Operators::make(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                          ->label(__('red-jasmine-product::product-service.fields.name'))
                          ->searchable(),
                TextColumn::make('description')
                          ->label(__('red-jasmine-product::product-service.fields.description'))
                          ->searchable(),
                TextColumn::make('icon')
                          ->label(__('red-jasmine-product::product-service.fields.icon'))
                ,
                ColorColumn::make('color')
                           ->label(__('red-jasmine-product::product-service.fields.color'))
                ,
                TextColumn::make('cluster')
                          ->label(__('red-jasmine-product::product-service.fields.cluster'))
                          ->searchable(),

                IconColumn::make('is_show')
                          ->label(__('red-jasmine-product::product-service.fields.is_show'))
                          ->boolean()
                ,
                TextColumn::make('sort')
                          ->label(__('red-jasmine-product::product-service.fields.sort'))
                          ->numeric()
                          ->sortable(),
                TextColumn::make('status')
                          ->label(__('red-jasmine-product::product-service.fields.status'))
                          ->useEnum(),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index'  => ListProductServices::route('/'),
            'create' => CreateProductService::route('/create'),
            'edit'   => EditProductService::route('/{record}/edit'),
        ];
    }

}
