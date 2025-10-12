<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductAttributeResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductAttributeResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource\Pages\ListProductAttributes;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeCreateCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeDeleteCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeApplicationService;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;

class ProductAttributeResource extends Resource
{
    protected static ?string $model = ProductAttribute::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    use ResourcePageHelper;

    protected static ?string $service        = ProductAttributeApplicationService::class;
    protected static ?string $commandService = ProductAttributeApplicationService::class;
    protected static ?string $createCommand = ProductAttributeCreateCommand::class;
    protected static ?string $updateCommand = ProductAttributeUpdateCommand::class;
    protected static ?string $deleteCommand = ProductAttributeDeleteCommand::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-attribute.labels.product-attribute');
    }

    protected static ?string $cluster = Product::class;


    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-attribute.labels.attribute');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([


                Forms\Components\ToggleButtons::make('type')
                                              ->label(__('red-jasmine-product::product-attribute.fields.type'))
                                              ->required()
                                              ->inline()
                                              ->default(ProductAttributeTypeEnum::SELECT)
                                              ->options(ProductAttributeTypeEnum::options()),
                Forms\Components\TextInput::make('name')->label(__('red-jasmine-product::product-attribute.fields.name'))
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('description')->label(__('red-jasmine-product::product-attribute.fields.description'))
                                          ->maxLength(255),

                Forms\Components\TextInput::make('unit')
                                          ->label(__('red-jasmine-product::product-attribute.fields.unit'))
                                          ->maxLength(10),
                Forms\Components\Select::make('group_id')
                                       ->label(__('red-jasmine-product::product-attribute.fields.group.name'))
                                       ->relationship('group', 'name')
                                       ->searchable(['name'])
                                       ->preload()
                                       ->nullable()
                                       ->saveRelationshipsUsing(null)
                                       ->defaultZero()
                ,

                Forms\Components\TextInput::make('sort')
                                          ->label(__('red-jasmine-product::product-attribute.fields.sort'))
                                          ->required()->integer()->default(0),
                Forms\Components\Radio::make('is_required')
                                      ->label(__('red-jasmine-product::product-attribute.fields.is_required'))
                                      ->default(false)->boolean()
                                      ->inline()->required(),
                Forms\Components\Radio::make('is_allow_multiple')
                                      ->label(__('red-jasmine-product::product-attribute.fields.is_allow_multiple'))
                                      ->default(false)->boolean()->inline()->required(),
                Forms\Components\Radio::make('is_allow_alias')
                                      ->label(__('red-jasmine-product::product-attribute.fields.is_allow_alias'))
                                      ->default(false)->boolean()->inline()
                                      ->required(),

                Forms\Components\ToggleButtons::make('status')
                                              ->label(__('red-jasmine-product::product-attribute.fields.status'))
                                              ->inline()
                                              ->required()
                                              ->grouped()
                                              ->default(ProductAttributeStatusEnum::ENABLE)
                                              ->useEnum(ProductAttributeStatusEnum::class)
                ,

                ...static::operateFormSchemas()


            ])
            ->columns(1);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label(__('red-jasmine-product::product-attribute.fields.id'))->copyable()->sortable(),
                Tables\Columns\TextColumn::make('group.name')->label(__('red-jasmine-product::product-attribute.fields.group.name'))->numeric(),
                Tables\Columns\TextColumn::make('type')->label(__('red-jasmine-product::product-attribute.fields.type'))
                                         ->useEnum(),
                Tables\Columns\TextColumn::make('name')->label(__('red-jasmine-product::product-attribute.fields.name'))->searchable(),
                Tables\Columns\TextColumn::make('unit')->label(__('red-jasmine-product::product-attribute.fields.unit'))
                ,
                Tables\Columns\IconColumn::make('is_required')->label(__('red-jasmine-product::product-attribute.fields.is_required'))->boolean(),
                Tables\Columns\IconColumn::make('is_allow_multiple')->label(__('red-jasmine-product::product-attribute.fields.is_allow_multiple'))->boolean(),
                Tables\Columns\IconColumn::make('is_allow_alias')->label(__('red-jasmine-product::product-attribute.fields.is_allow_alias'))->boolean(),
                Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine-product::product-attribute.fields.sort'))->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product-attribute.fields.status'))
                                         ->useEnum(),
                ...static::operateTableColumns()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group_id')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.group.name'))
                                           ->relationship('group', 'name')
                                           ->searchable()
                                           ->optionsLimit(50)
                                           ->preload(),
                Tables\Filters\SelectFilter::make('status')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.status'))
                                           ->options(ProductAttributeStatusEnum::options()),

                Tables\Filters\TernaryFilter::make('is_required')
                                            ->label(__('red-jasmine-product::product-attribute.fields.is_required'))
                                            ->boolean(true),
                Tables\Filters\TernaryFilter::make('is_allow_multiple')
                                            ->label(__('red-jasmine-product::product-attribute.fields.is_allow_multiple'))
                                            ->boolean(true),
                Tables\Filters\TernaryFilter::make('is_allow_alias')
                                            ->label(__('red-jasmine-product::product-attribute.fields.is_allow_alias'))
                                            ->boolean(true),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordUrl(null)
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
            'index'  => ListProductAttributes::route('/'),
            'create' => Product\Resources\ProductAttributeResource\Pages\CreateProductAttribute::route('/create'),
            'view'   => Product\Resources\ProductAttributeResource\Pages\ViewProductAttribute::route('/{record}'),
            'edit'   => Product\Resources\ProductAttributeResource\Pages\EditProductAttribute::route('/{record}/edit'),
        ];
    }


}
