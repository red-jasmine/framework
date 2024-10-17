<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServiceResource\Pages;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServiceResource\RelationManagers;
use RedJasmine\Product\Application\Service\Services\ProductServiceCommandService;
use RedJasmine\Product\Application\Service\Services\ProductServiceQueryService;
use RedJasmine\Product\Application\Service\UserCases\Commands\ProductServiceCreateCommand;
use RedJasmine\Product\Application\Service\UserCases\Commands\ProductServiceDeleteCommand;
use RedJasmine\Product\Application\Service\UserCases\Commands\ProductServiceUpdateCommand;
use RedJasmine\Product\Domain\Service\Models\Enums\ServiceStatusEnum;
use RedJasmine\Product\Domain\Service\Models\ProductService;

class ProductServiceResource extends Resource
{
    protected static ?string $model = ProductService::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $cluster = Product::class;


    protected static ?int $navigationSort = 6;

    use ResourcePageHelper;

    protected static ?string $commandService = ProductServiceCommandService::class;
    protected static ?string $queryService   = ProductServiceQueryService::class;
    protected static ?string $createCommand  = ProductServiceCreateCommand::class;
    protected static ?string $updateCommand  = ProductServiceUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductServiceDeleteCommand::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-service.labels.service');
    }


    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         Forms\Components\TextInput::make('name')
                                                   ->label(__('red-jasmine-product::product-service.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')
                                                   ->label(__('red-jasmine-product::product-service.fields.description'))
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('icon')
                                                   ->label(__('red-jasmine-product::product-service.fields.icon'))
                                                   ->maxLength(255),
                         Forms\Components\ColorPicker::make('color')
                                                     ->label(__('red-jasmine-product::product-service.fields.color'))
                         ,
                         Forms\Components\TextInput::make('cluster')
                                                   ->label(__('red-jasmine-product::product-service.fields.cluster'))
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('sort')
                                                   ->label(__('red-jasmine-product::product-service.fields.sort'))
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0),
                         Forms\Components\Radio::make('is_show')
                                               ->label(__('red-jasmine-product::product-service.fields.is_show'))
                                               ->required()
                                               ->boolean()->inline()
                                               ->default(1),
                         Forms\Components\ToggleButtons::make('status')
                                                       ->label(__('red-jasmine-product::product-service.fields.status'))
                                                       ->required()
                                                       ->grouped()
                                                       ->default(32)
                                                       ->default(ServiceStatusEnum::ENABLE)
                                                       ->useEnum(ServiceStatusEnum::class),
                         ...static::operateFormSchemas()
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('name')
                                                   ->label(__('red-jasmine-product::product-service.fields.name'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('description')
                                                   ->label(__('red-jasmine-product::product-service.fields.description'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('icon')
                                                   ->label(__('red-jasmine-product::product-service.fields.icon'))
                          ,
                          Tables\Columns\ColorColumn::make('color')
                                                    ->label(__('red-jasmine-product::product-service.fields.color'))
                          ,
                          Tables\Columns\TextColumn::make('cluster')
                                                   ->label(__('red-jasmine-product::product-service.fields.cluster'))
                                                   ->searchable(),

                          Tables\Columns\IconColumn::make('is_show')
                                                   ->label(__('red-jasmine-product::product-service.fields.is_show'))
                                                   ->boolean()
                          ,
                          Tables\Columns\TextColumn::make('sort')
                                                   ->label(__('red-jasmine-product::product-service.fields.sort'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('status')
                                                   ->label(__('red-jasmine-product::product-service.fields.status'))
                                                   ->useEnum(),
                          ...static::operateTableColumns(),
                      ])
            ->filters([
                          Tables\Filters\TrashedFilter::make(),
                      ])
            ->actions([
                          Tables\Actions\EditAction::make(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                       Tables\Actions\ForceDeleteBulkAction::make(),
                                                                       Tables\Actions\RestoreBulkAction::make(),
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
            'index'  => Pages\ListProductServices::route('/'),
            'create' => Pages\CreateProductService::route('/create'),
            'edit'   => Pages\EditProductService::route('/{record}/edit'),
        ];
    }

}
