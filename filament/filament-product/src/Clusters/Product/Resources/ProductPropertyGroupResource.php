<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages\EditProductPropertyGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages\ListProductPropertyGroups;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages\ViewProductPropertyGroup;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyGroupDeleteCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupApplicationService;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;

class ProductPropertyGroupResource extends Resource
{
    protected static ?string $cluster = Product::class;
    protected static ?string $model   = ProductPropertyGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';


    use ResourcePageHelper;

    protected static ?string $service        = ProductPropertyGroupApplicationService::class;
    protected static ?string $commandService = ProductPropertyGroupApplicationService::class;
    protected static ?string $createCommand  = ProductPropertyGroupCreateCommand::class;
    protected static ?string $updateCommand  = ProductPropertyGroupUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductPropertyGroupDeleteCommand::class;
    protected static ?int    $navigationSort = 6;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-property-group.labels.product-property-group');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-property.labels.property');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine-product::product-property-group.fields.name'))
                                          ->required()->maxLength(255),
                Forms\Components\TextInput::make('description')
                                          ->label(__('red-jasmine-product::product-property-group.fields.description'))
                                          ->maxLength(255),
                Forms\Components\TextInput::make('sort')->label(__('red-jasmine-product::product-property-group.fields.sort'))
                                          ->required()
                                          ->integer()
                                          ->default(0),
                Forms\Components\ToggleButtons::make('status')->label(__('red-jasmine-product::product-property-group.fields.status'))
                                              ->inline()
                                              ->required()
                                              ->default(PropertyStatusEnum::ENABLE)
                                              ->useEnum(PropertyStatusEnum::class)
                ,

                ...static::operateFormSchemas()
            ])->columns(1);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-product::product-property-group.fields.id'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')->label(__('red-jasmine-product::product-property-group.fields.name'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine-product::product-property-group.fields.sort'))
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product-property-group.fields.status'))
                                         ->useEnum(),


                ...static::operateTableColumns()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                                           ->label(__('red-jasmine-product::product-property-value.fields.status'))
                                           ->options(PropertyStatusEnum::options()),
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
            'index'  => ListProductPropertyGroups::route('/'),
            'create' => Product\Resources\ProductPropertyGroupResource\Pages\CreateProductPropertyGroup::route('/create'),
            'view'   => ViewProductPropertyGroup::route('/{record}'),
            'edit'   => EditProductPropertyGroup::route('/{record}/edit'),
        ];
    }


}
