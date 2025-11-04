<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValueResource\Pages\CreateProductAttributeValue;
use App\Filament\Clusters\Product\Resources\ProductAttributeValueResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductAttributeValueResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValueResource\Pages\EditProductAttributeValue;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValueResource\Pages\ListProductAttributeValues;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValueResource\Pages\ViewProductAttributeValue;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueCreateCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueDeleteCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValueApplicationService;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;

class ProductAttributeValueResource extends Resource
{
    protected static ?string $model = ProductAttributeValue::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $cluster        = Product::class;


    use ResourcePageHelper;

    protected static ?string $service        = ProductAttributeValueApplicationService::class;
    protected static ?string $commandService = ProductAttributeValueApplicationService::class;
    protected static ?string $createCommand  = ProductAttributeValueCreateCommand::class;
    protected static ?string $updateCommand  = ProductAttributeValueUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductAttributeValueDeleteCommand::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-attribute-value.labels.product-attribute-value');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-attribute.labels.attribute');
    }

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel()
            ->components([
                Select::make('aid')
                                       ->label(__('red-jasmine-product::product-attribute-value.fields.aid'))
                                       ->required()
                                       ->relationship('attribute', 'name')
                                       ->searchable(['name'])
                                       ->preload()
                                       ->optionsLimit(50)
                ,
                TextInput::make('name')
                                          ->label(__('red-jasmine-product::product-attribute-value.fields.name'))
                                          ->required()
                                          ->maxLength(64),
                Select::make('group_id')
                                       ->label(__('red-jasmine-product::product-attribute-value.fields.group_id'))
                                       ->relationship('group', 'name')
                                       ->searchable(['name'])
                                       ->preload()
                                       ->nullable()
                                       ->saveRelationshipsUsing(null)
                                       ->defaultZero()
                                       ->optionsLimit(50)
                ,


                TextInput::make('description')
                                          ->label(__('red-jasmine-product::product-attribute-value.fields.description'))->maxLength(255),
                TextInput::make('sort')
                                          ->label(__('red-jasmine-product::product-attribute-value.fields.sort'))
                                          ->required()->integer()->default(0),
                ToggleButtons::make('status')
                                              ->label(__('red-jasmine-product::product-attribute-value.fields.status'))
                                              ->required()
                                              ->inline()
                                              ->default(ProductAttributeStatusEnum::ENABLE)
                                              ->useEnum(ProductAttributeStatusEnum::class),

                ...static::operateFormSchemas()
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                                         ->label('ID')
                                         ->label(__('red-jasmine-product::product-attribute-value.fields.id'))
                                         ->copyable()
                                         ->sortable(),
                TextColumn::make('attribute.name')
                                         ->label(__('red-jasmine-product::product-attribute-value.fields.attribute.name')),

                TextColumn::make('name')
                                         ->label(__('red-jasmine-product::product-attribute-value.fields.name'))
                                         ->copyable()
                                         ->searchable()
                ,
                TextColumn::make('group.name')->label(__('red-jasmine-product::product-attribute-value.fields.group.name')),
                TextColumn::make('sort')->label(__('red-jasmine-product::product-attribute-value.fields.sort'))->sortable(),
                TextColumn::make('status')->label(__('red-jasmine-product::product-attribute-value.fields.status'))
                                         ->useEnum(),

                ...static::operateTableColumns()
            ])
            ->filters([
                SelectFilter::make('aid')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.attribute.name'))
                                           ->relationship('attribute', 'name')
                                           ->searchable()
                                           ->optionsLimit(50)
                                           ->preload(),

                SelectFilter::make('group_id')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.group.name'))
                                           ->relationship('group', 'name')
                                           ->searchable()
                                           ->optionsLimit(50)
                                           ->preload(),
                SelectFilter::make('status')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.status'))
                                           ->options(ProductAttributeStatusEnum::options())
                ,
                TrashedFilter::make(),


            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
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
            'index'  => ListProductAttributeValues::route('/'),
            'create' => CreateProductAttributeValue::route('/create'),
            'view'   => ViewProductAttributeValue::route('/{record}'),
            'edit'   => EditProductAttributeValue::route('/{record}/edit'),
        ];
    }


}
