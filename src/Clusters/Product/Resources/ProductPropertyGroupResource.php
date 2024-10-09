<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupQueryService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupDeleteCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;

class ProductPropertyGroupResource extends Resource
{
    protected static ?string $cluster = Product::class;
    protected static ?string $model   = ProductPropertyGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';


    use ResourcePageHelper;

    protected static ?string $commandService = ProductPropertyGroupCommandService::class;
    protected static ?string $queryService   = ProductPropertyGroupQueryService::class;
    protected static ?string $createCommand  = ProductPropertyGroupCreateCommand::class;
    protected static ?string $updateCommand  = ProductPropertyGroupUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductPropertyGroupDeleteCommand::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-property-group.labels.product-property-group');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-property.labels.product-property');
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
                                                   ->required()->integer()->default(0),
                         Forms\Components\Radio::make('status')->label(__('red-jasmine-product::product-property-group.fields.status'))
                                               ->required()
                                               ->default(PropertyStatusEnum::ENABLE)->options(PropertyStatusEnum::options())
                                               ->inline()->inlineLabel(false)->required(),
                         Forms\Components\TextInput::make('creator_type')->label(__('red-jasmine-product::product-property-group.fields.creator_type'))
                                                   ->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('creator_id')->label(__('red-jasmine-product::product-property-group.fields.creator_id'))
                                                   ->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_type')->label(__('red-jasmine-product::product-property-group.fields.updater_type'))
                                                   ->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_id')->label(__('red-jasmine-product::product-property-group.fields.updater_id'))
                                                   ->readOnly()->visibleOn('view'),
                     ])->columns(1);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                          ->label(__('red-jasmine-product::product-property-group.fields.id'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('name')->label(__('red-jasmine-product::product-property-group.fields.name'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine-product::product-property-group.fields.sort'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product-property-group.fields.status'))
                                                   ->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('creator_type')->label(__('red-jasmine-product::product-property-group.fields.creator_type'))
                                                   ->searchable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('creator_id')->label(__('red-jasmine-product::product-property-group.fields.creator_id'))
                                                   ->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updater_type')->label(__('red-jasmine-product::product-property-group.fields.updater_type'))
                                                   ->searchable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updater_id')->label(__('red-jasmine-product::product-property-group.fields.updater_id'))
                                                   ->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('created_at')->label(__('red-jasmine-product::product-property-group.fields.created_at'))
                                                   ->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updated_at')->label(__('red-jasmine-product::product-property-group.fields.updated_at'))
                                                   ->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('deleted_at')->label(__('red-jasmine-product::product-property-group.fields.deleted_at'))
                                                   ->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                      ])
            ->filters([
                          Tables\Filters\TrashedFilter::make(),
                      ])
            ->deferFilters()
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
            'index'  => \RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages\ListProductPropertyGroups::route('/'),
            'create' => Product\Resources\ProductPropertyGroupResource\Pages\CreateProductPropertyGroup::route('/create'),
            'view'   => \RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages\ViewProductPropertyGroup::route('/{record}'),
            'edit'   => \RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages\EditProductPropertyGroup::route('/{record}/edit'),
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
