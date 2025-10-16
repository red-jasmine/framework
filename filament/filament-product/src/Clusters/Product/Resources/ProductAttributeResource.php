<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource\Pages\CreateProductAttribute;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource\Pages\ViewProductAttribute;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource\Pages\EditProductAttribute;
use App\Filament\Clusters\Product\Resources\ProductAttributeResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductAttributeResource\RelationManagers;
use Filament\Forms;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cube-transparent';

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

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([


                ToggleButtons::make('type')
                                              ->label(__('red-jasmine-product::product-attribute.fields.type'))
                                              ->required()
                                              ->inline()
                                              ->default(ProductAttributeTypeEnum::SELECT)
                                              ->options(ProductAttributeTypeEnum::options()),
                TextInput::make('name')->label(__('red-jasmine-product::product-attribute.fields.name'))
                                          ->required()
                                          ->maxLength(255),
                TextInput::make('description')->label(__('red-jasmine-product::product-attribute.fields.description'))
                                          ->maxLength(255),

                TextInput::make('unit')
                                          ->label(__('red-jasmine-product::product-attribute.fields.unit'))
                                          ->maxLength(10),
                Select::make('group_id')
                                       ->label(__('red-jasmine-product::product-attribute.fields.group.name'))
                                       ->relationship('group', 'name')
                                       ->searchable(['name'])
                                       ->preload()
                                       ->nullable()
                                       ->saveRelationshipsUsing(null)
                                       ->defaultZero()
                ,

                TextInput::make('sort')
                                          ->label(__('red-jasmine-product::product-attribute.fields.sort'))
                                          ->required()->integer()->default(0),
                Radio::make('is_required')
                                      ->label(__('red-jasmine-product::product-attribute.fields.is_required'))
                                      ->default(false)->boolean()
                                      ->inline()->required(),
                Radio::make('is_allow_multiple')
                                      ->label(__('red-jasmine-product::product-attribute.fields.is_allow_multiple'))
                                      ->default(false)->boolean()->inline()->required(),
                Radio::make('is_allow_alias')
                                      ->label(__('red-jasmine-product::product-attribute.fields.is_allow_alias'))
                                      ->default(false)->boolean()->inline()
                                      ->required(),

                ToggleButtons::make('status')
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
                TextColumn::make('id')->label(__('red-jasmine-product::product-attribute.fields.id'))->copyable()->sortable(),
                TextColumn::make('group.name')->label(__('red-jasmine-product::product-attribute.fields.group.name'))->numeric(),
                TextColumn::make('type')->label(__('red-jasmine-product::product-attribute.fields.type'))
                                         ->useEnum(),
                TextColumn::make('name')->label(__('red-jasmine-product::product-attribute.fields.name'))->searchable(),
                TextColumn::make('unit')->label(__('red-jasmine-product::product-attribute.fields.unit'))
                ,
                IconColumn::make('is_required')->label(__('red-jasmine-product::product-attribute.fields.is_required'))->boolean(),
                IconColumn::make('is_allow_multiple')->label(__('red-jasmine-product::product-attribute.fields.is_allow_multiple'))->boolean(),
                IconColumn::make('is_allow_alias')->label(__('red-jasmine-product::product-attribute.fields.is_allow_alias'))->boolean(),
                TextColumn::make('sort')->label(__('red-jasmine-product::product-attribute.fields.sort'))->sortable(),
                TextColumn::make('status')->label(__('red-jasmine-product::product-attribute.fields.status'))
                                         ->useEnum(),
                ...static::operateTableColumns()
            ])
            ->filters([
                SelectFilter::make('group_id')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.group.name'))
                                           ->relationship('group', 'name')
                                           ->searchable()
                                           ->optionsLimit(50)
                                           ->preload(),
                SelectFilter::make('status')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.status'))
                                           ->options(ProductAttributeStatusEnum::options()),

                TernaryFilter::make('is_required')
                                            ->label(__('red-jasmine-product::product-attribute.fields.is_required'))
                                            ->boolean(true),
                TernaryFilter::make('is_allow_multiple')
                                            ->label(__('red-jasmine-product::product-attribute.fields.is_allow_multiple'))
                                            ->boolean(true),
                TernaryFilter::make('is_allow_alias')
                                            ->label(__('red-jasmine-product::product-attribute.fields.is_allow_alias'))
                                            ->boolean(true),
                TrashedFilter::make(),
            ])
            ->recordUrl(null)
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
            'index'  => ListProductAttributes::route('/'),
            'create' => CreateProductAttribute::route('/create'),
            'view'   => ViewProductAttribute::route('/{record}'),
            'edit'   => EditProductAttribute::route('/{record}/edit'),
        ];
    }


}
