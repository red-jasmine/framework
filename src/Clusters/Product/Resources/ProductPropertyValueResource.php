<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueQueryService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueDeleteCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;

class ProductPropertyValueResource extends Resource
{
    protected static ?string $model = ProductPropertyValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $cluster        = Product::class;


    use ResourcePageHelper;

    protected static ?string $commandService = ProductPropertyValueCommandService::class;
    protected static ?string $queryService   = ProductPropertyValueQueryService::class;
    protected static ?string $createCommand  = ProductPropertyValueCreateCommand::class;
    protected static ?string $updateCommand  = ProductPropertyValueUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductPropertyValueDeleteCommand::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-property-value.labels.product-property-value');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-property.labels.product-property');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         Forms\Components\Select::make('pid')
                             ->label(__('red-jasmine-product::product-property-value.fields.pid'))
                                                ->required()
                                                ->relationship('property', 'name')
                                                ->searchable([ 'name' ])
                                                ->preload()->optionsLimit(2)
                                                ->nullable(),

                         Forms\Components\Select::make('group_id')
                             ->label(__('red-jasmine-product::product-property-value.fields.group_id'))

                             ->relationship('group', 'name')
                                                ->searchable([ 'name' ])
                                                ->preload()
                                                ->nullable(),
                         Forms\Components\TextInput::make('name')
                             ->label(__('red-jasmine-product::product-property-value.fields.name'))
                                                   ->required()
                                                   ->maxLength(64),
                         Forms\Components\TextInput::make('description')
                             ->label(__('red-jasmine-product::product-property-value.fields.description'))->maxLength(255),
                         Forms\Components\TextInput::make('sort')
                             ->label(__('red-jasmine-product::product-property-value.fields.sort'))
                             ->required()->integer()->default(0),
                         Forms\Components\Radio::make('status')
                             ->label(__('red-jasmine-product::product-property-value.fields.status'))
                                               ->required()
                                               ->default(PropertyStatusEnum::ENABLE)->options(PropertyStatusEnum::options())
                                               ->inline()->inlineLabel(false)->required(),
                         Forms\Components\TextInput::make('creator_type')->label(__('red-jasmine-product::product-property-value.fields.status'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('creator_id')->label(__('red-jasmine-product::product-property-value.fields.status'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_type')->label(__('red-jasmine-product::product-property-value.fields.status'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_id')->label(__('red-jasmine-product::product-property-value.fields.status'))->readOnly()->visibleOn('view'),
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
                                                   ->copyable(),
                          Tables\Columns\TextColumn::make('group.name')->label(__('red-jasmine-product::product-property-value.fields.group.name')),
                          Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine-product::product-property-value.fields.sort'))->sortable(),
                          Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product-property-value.fields.status'))->enum(),
                          Tables\Columns\TextColumn::make('creator_type')->label(__('red-jasmine-product::product-property-value.fields.creator_type'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('creator_id')->label(__('red-jasmine-product::product-property-value.fields.creator_id'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updater_type')->label(__('red-jasmine-product::product-property-value.fields.updater_type'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updater_id')->label(__('red-jasmine-product::product-property-value.fields.updater_id'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('created_at')->label(__('red-jasmine-product::product-property-value.fields.created_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updated_at')->label(__('red-jasmine-product::product-property-value.fields.updated_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('deleted_at')->label(__('red-jasmine-product::product-property-value.fields.deleted_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                      ])
            ->filters([
                          Tables\Filters\TrashedFilter::make(),
                      ])
            ->actions([
                          Tables\Actions\ViewAction::make(),
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
            'index'  => \RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages\ListProductPropertyValues::route('/'),
            'create' => Product\Resources\ProductPropertyValueResource\Pages\CreateProductPropertyValue::route('/create'),
            'view'   => \RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages\ViewProductPropertyValue::route('/{record}'),
            'edit'   => \RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages\EditProductPropertyValue::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery() : Builder
    {
        return parent::getEloquentQuery()
                     ->withoutGlobalScopes([
                                               SoftDeletingScope::class,
                                           ]);
    }
}
