<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductAttributeGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductAttributeGroupResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';


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

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine-product::product-attribute-group.fields.name'))
                                          ->required()->maxLength(255),
                Forms\Components\TextInput::make('description')
                                          ->label(__('red-jasmine-product::product-attribute-group.fields.description'))
                                          ->maxLength(255),
                Forms\Components\TextInput::make('sort')->label(__('red-jasmine-product::product-attribute-group.fields.sort'))
                                          ->required()
                                          ->integer()
                                          ->default(0),
                Forms\Components\ToggleButtons::make('status')->label(__('red-jasmine-product::product-attribute-group.fields.status'))
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
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-product::product-attribute-group.fields.id'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')->label(__('red-jasmine-product::product-attribute-group.fields.name'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine-product::product-attribute-group.fields.sort'))
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product-attribute-group.fields.status'))
                                         ->useEnum(),


                ...static::operateTableColumns()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                                           ->label(__('red-jasmine-product::product-attribute-value.fields.status'))
                                           ->options(ProductAttributeStatusEnum::options()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->deferFilters()
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
            'index'  => ListProductAttributeGroups::route('/'),
            'create' => Product\Resources\ProductAttributeGroupResource\Pages\CreateProductAttributeGroup::route('/create'),
            'view'   => ViewProductAttributeGroup::route('/{record}'),
            'edit'   => EditProductAttributeGroup::route('/{record}/edit'),
        ];
    }


}
