<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductPropertyResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductPropertyResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyQueryService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyDeleteCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;

class ProductPropertyResource extends Resource
{
    protected static ?string $model = ProductProperty::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    use ResourcePageHelper;

    protected static ?string $commandService = ProductPropertyCommandService::class;
    protected static ?string $queryService   = ProductPropertyQueryService::class;
    protected static ?string $createCommand  = ProductPropertyCreateCommand::class;
    protected static ?string $updateCommand  = ProductPropertyUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductPropertyDeleteCommand::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::product-property.labels.product-property');
    }

    protected static ?string $cluster = Product::class;


    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine.product::product-property.labels.product-property');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([

                         Forms\Components\Radio::make('type')
                                               ->label(__('red-jasmine.product::product-property.fields.type'))
                                               ->required()
                                               ->inline()
                                               ->inlineLabel(false)
                                               ->default(PropertyTypeEnum::SELECT)
                                               ->options(PropertyTypeEnum::options()),
                         Forms\Components\TextInput::make('name')->label(__('red-jasmine.product::product-property.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')->label(__('red-jasmine.product::product-property.fields.description'))
                                                   ->maxLength(255),

                         Forms\Components\TextInput::make('unit')
                                                   ->label(__('red-jasmine.product::product-property.fields.unit'))
                                                   ->maxLength(10),
                         Forms\Components\Select::make('group_id')
                                                ->label(__('red-jasmine.product::product-property.fields.group.name'))
                                                ->relationship('group', 'name')
                                                ->searchable([ 'name' ])
                                                ->preload()
                                                ->nullable(),
                         Forms\Components\Radio::make('is_allow_multiple')
                                               ->label(__('red-jasmine.product::product-property.fields.is_allow_multiple'))
                                               ->default(false)->boolean()->inline()->inlineLabel(false)->required(),
                         Forms\Components\Radio::make('is_allow_alias')
                                               ->label(__('red-jasmine.product::product-property.fields.is_allow_alias'))->default(false)->boolean()->inline()->inlineLabel(false)->required(),

                         Forms\Components\TextInput::make('sort')->label(__('red-jasmine.product::product-property.fields.sort'))->required()->integer()->default(0),
                         Forms\Components\Radio::make('status')->label(__('red-jasmine.product::product-property.fields.status'))
                                               ->required()
                                               ->default(PropertyStatusEnum::ENABLE)->options(PropertyStatusEnum::options())
                                               ->inline()->inlineLabel(false)->required(),
                         Forms\Components\TextInput::make('creator_type')->label(__('red-jasmine.product::product-property.fields.creator_type'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('creator_id')->label(__('red-jasmine.product::product-property.fields.creator_id'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_type')->label(__('red-jasmine.product::product-property.fields.updater_type'))->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_id')->label(__('red-jasmine.product::product-property.fields.updater_id'))->readOnly()->visibleOn('view'),
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')->label(__('red-jasmine.product::product-property.fields.id'))->copyable()->sortable(),
                          Tables\Columns\TextColumn::make('group.name')->label(__('red-jasmine.product::product-property.fields.group.name'))->numeric(),
                          Tables\Columns\TextColumn::make('type')->label(__('red-jasmine.product::product-property.fields.type'))->badge()->formatStateUsing(fn($state
                          ) => PropertyTypeEnum::options()[$state->value])->color(fn($state
                          ) => PropertyTypeEnum::colors()[$state->value]),
                          Tables\Columns\TextColumn::make('name')->label(__('red-jasmine.product::product-property.fields.name'))->searchable(),
                          Tables\Columns\TextColumn::make('unit')->label(__('red-jasmine.product::product-property.fields.unit'))
                          ,
                          Tables\Columns\IconColumn::make('is_allow_multiple')->label(__('red-jasmine.product::product-property.fields.is_allow_multiple'))->boolean(),
                          Tables\Columns\IconColumn::make('is_allow_alias')->label(__('red-jasmine.product::product-property.fields.is_allow_alias'))->boolean(),
                          Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine.product::product-property.fields.sort'))->sortable(),
                          Tables\Columns\TextColumn::make('status')->label(__('red-jasmine.product::product-property.fields.status'))->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('creator_type')->label(__('red-jasmine.product::product-property.fields.creator_type'))->searchable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('creator_id')->label(__('red-jasmine.product::product-property.fields.creator_id'))->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updater_type')->searchable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updater_id')->label(__('red-jasmine.product::product-property.fields.updater_id'))->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('created_at')->label(__('red-jasmine.product::product-property.fields.created_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updated_at')->label(__('red-jasmine.product::product-property.fields.updated_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('deleted_at')->label(__('red-jasmine.product::product-property.fields.deleted_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                      ])
            ->filters([
                          Tables\Filters\TrashedFilter::make(),
                      ])
            ->recordUrl(null)
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
            'index'  => \RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource\Pages\ListProductProperties::route('/'),
            'create' => Product\Resources\ProductPropertyResource\Pages\CreateProductProperty::route('/create'),
            'view'   => Product\Resources\ProductPropertyResource\Pages\ViewProductProperty::route('/{record}'),
            'edit'   => Product\Resources\ProductPropertyResource\Pages\EditProductProperty::route('/{record}/edit'),
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
