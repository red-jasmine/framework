<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Filament\Schemas\Schema;
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
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource\Pages\CreateProductAttributeGroup;
use App\Filament\Clusters\Product\Resources\ProductAttributeGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductAttributeGroupResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource\Pages\EditProductAttributeGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource\Pages\ListProductAttributeGroups;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource\Pages\ViewProductAttributeGroup;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeGroupCreateCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeGroupDeleteCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeGroupUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeGroupApplicationService;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeGroup;

class ProductAttributeGroupResource extends Resource
{
    protected static ?string $cluster = Product::class;
    protected static ?string $model   = ProductAttributeGroup::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-list-bullet';


    use ResourcePageHelper;

    protected static ?string $service        = ProductAttributeGroupApplicationService::class;
    protected static ?string $commandService = ProductAttributeGroupApplicationService::class;
    protected static ?string $createCommand  = ProductAttributeGroupCreateCommand::class;
    protected static ?string $updateCommand  = ProductAttributeGroupUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductAttributeGroupDeleteCommand::class;
    protected static ?int    $navigationSort = 6;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-attribute-group.labels.product-attribute-group');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-attribute.labels.attribute');
    }

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                                          ->label(__('red-jasmine-product::product-attribute-group.fields.name'))
                                          ->required()->maxLength(255),
                TextInput::make('description')
                                          ->label(__('red-jasmine-product::product-attribute-group.fields.description'))
                                          ->maxLength(255),
                TextInput::make('sort')->label(__('red-jasmine-product::product-attribute-group.fields.sort'))
                                          ->required()
                                          ->integer()
                                          ->default(0),
                ToggleButtons::make('status')->label(__('red-jasmine-product::product-attribute-group.fields.status'))
                                              ->inline()
                                              ->required()
                                              ->default(ProductAttributeStatusEnum::ENABLE)
                                              ->useEnum(ProductAttributeStatusEnum::class)
                ,

                ...static::operateFormSchemas()
            ])->columns(1);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                                         ->label(__('red-jasmine-product::product-attribute-group.fields.id'))
                                         ->sortable(),
                TextColumn::make('name')->label(__('red-jasmine-product::product-attribute-group.fields.name'))
                                         ->searchable(),
                TextColumn::make('sort')->label(__('red-jasmine-product::product-attribute-group.fields.sort'))
                                         ->numeric()
                                         ->sortable(),
                TextColumn::make('status')->label(__('red-jasmine-product::product-attribute-group.fields.status'))
                                         ->useEnum(),


                ...static::operateTableColumns()
            ])
            ->filters([
                SelectFilter::make('status')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.status'))
                                           ->options(ProductAttributeStatusEnum::options()),
                TrashedFilter::make(),
            ])
            ->deferFilters()
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
            'index'  => ListProductAttributeGroups::route('/'),
            'create' => CreateProductAttributeGroup::route('/create'),
            'view'   => ViewProductAttributeGroup::route('/{record}'),
            'edit'   => EditProductAttributeGroup::route('/{record}/edit'),
        ];
    }


}
