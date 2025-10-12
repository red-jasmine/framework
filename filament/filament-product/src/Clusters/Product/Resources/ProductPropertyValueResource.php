<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages\EditProductPropertyValue;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages\ListProductPropertyValues;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages\ViewProductPropertyValue;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueCreateCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueDeleteCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValueApplicationService;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;

class ProductPropertyValueResource extends Resource
{
    protected static ?string $model = ProductAttributeValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $cluster        = Product::class;


    use ResourcePageHelper;

    protected static ?string $service        = ProductAttributeValueApplicationService::class;
    protected static ?string $commandService = ProductAttributeValueApplicationService::class;
    protected static ?string $createCommand  = ProductAttributeValueCreateCommand::class;
    protected static ?string $updateCommand  = ProductAttributeValueUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductAttributeValueDeleteCommand::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-property-value.labels.product-property-value');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-property.labels.property');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->columns(1)
            ->inlineLabel()
            ->schema([
                Forms\Components\Select::make('pid')
                                       ->label(__('red-jasmine-product::product-property-value.fields.pid'))
                                       ->required()
                                       ->relationship('property', 'name')
                                       ->searchable(['name'])
                                       ->preload()
                                       ->optionsLimit(50)
                ,
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine-product::product-property-value.fields.name'))
                                          ->required()
                                          ->maxLength(64),
                Forms\Components\Select::make('group_id')
                                       ->label(__('red-jasmine-product::product-property-value.fields.group_id'))
                                       ->relationship('group', 'name')
                                       ->searchable(['name'])
                                       ->preload()
                                       ->nullable()
                                       ->saveRelationshipsUsing(null)
                                       ->defaultZero()
                                       ->optionsLimit(50)
                ,


                Forms\Components\TextInput::make('description')
                                          ->label(__('red-jasmine-product::product-property-value.fields.description'))->maxLength(255),
                Forms\Components\TextInput::make('sort')
                                          ->label(__('red-jasmine-product::product-property-value.fields.sort'))
                                          ->required()->integer()->default(0),
                Forms\Components\ToggleButtons::make('status')
                                              ->label(__('red-jasmine-product::product-property-value.fields.status'))
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
                Tables\Columns\TextColumn::make('id')
                                         ->label('ID')
                                         ->label(__('red-jasmine-product::product-property-value.fields.id'))
                                         ->copyable()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('property.name')
                                         ->label(__('red-jasmine-product::product-property-value.fields.property.name')),

                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-product::product-property-value.fields.name'))
                                         ->copyable()
                                         ->searchable()
                ,
                Tables\Columns\TextColumn::make('group.name')->label(__('red-jasmine-product::product-property-value.fields.group.name')),
                Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine-product::product-property-value.fields.sort'))->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product-property-value.fields.status'))
                                         ->useEnum(),

                ...static::operateTableColumns()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pid')
                                           ->label(__('red-jasmine-product::product-property-value.fields.property.name'))
                                           ->relationship('property', 'name')
                                           ->searchable()
                                           ->optionsLimit(50)
                                           ->preload(),

                Tables\Filters\SelectFilter::make('group_id')
                                           ->label(__('red-jasmine-product::product-property-value.fields.group.name'))
                                           ->relationship('group', 'name')
                                           ->searchable()
                                           ->optionsLimit(50)
                                           ->preload(),
                Tables\Filters\SelectFilter::make('status')
                                           ->label(__('red-jasmine-product::product-property-value.fields.status'))
                                           ->options(ProductAttributeStatusEnum::options())
                ,
                Tables\Filters\TrashedFilter::make(),


            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
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
            'index'  => ListProductPropertyValues::route('/'),
            'create' => Product\Resources\ProductPropertyValueResource\Pages\CreateProductPropertyValue::route('/create'),
            'view'   => ViewProductPropertyValue::route('/{record}'),
            'edit'   => EditProductPropertyValue::route('/{record}/edit'),
        ];
    }


}
