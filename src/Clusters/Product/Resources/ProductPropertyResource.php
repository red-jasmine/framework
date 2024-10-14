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
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
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
        return __('red-jasmine-product::product-property.labels.product-property');
    }

    protected static ?string $cluster = Product::class;


    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-property.labels.property');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([


                         Forms\Components\ToggleButtons::make('type')
                                                       ->label(__('red-jasmine-product::product-property.fields.type'))
                                                       ->required()
                                                       ->inline()
                                                       ->default(PropertyTypeEnum::SELECT)
                                                       ->options(PropertyTypeEnum::options()),
                         Forms\Components\TextInput::make('name')->label(__('red-jasmine-product::product-property.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')->label(__('red-jasmine-product::product-property.fields.description'))
                                                   ->maxLength(255),

                         Forms\Components\TextInput::make('unit')
                                                   ->label(__('red-jasmine-product::product-property.fields.unit'))
                                                   ->maxLength(10),
                         Forms\Components\Select::make('group_id')
                                                ->label(__('red-jasmine-product::product-property.fields.group.name'))
                                                ->relationship('group', 'name')
                                                ->searchable([ 'name' ])
                                                ->preload()
                                                ->nullable()
                                                ->saveRelationshipsUsing(null)
                                                ->defaultZero()
                         ,

                         Forms\Components\TextInput::make('sort')
                                                   ->label(__('red-jasmine-product::product-property.fields.sort'))
                                                   ->required()->integer()->default(0),
                         Forms\Components\Radio::make('is_required')
                                               ->label(__('red-jasmine-product::product-property.fields.is_required'))
                                               ->default(false)->boolean()
                                               ->inline()->required(),
                         Forms\Components\Radio::make('is_allow_multiple')
                                               ->label(__('red-jasmine-product::product-property.fields.is_allow_multiple'))
                                               ->default(false)->boolean()->inline()->required(),
                         Forms\Components\Radio::make('is_allow_alias')
                                               ->label(__('red-jasmine-product::product-property.fields.is_allow_alias'))
                                               ->default(false)->boolean()->inline()
                                               ->required(),

                         Forms\Components\ToggleButtons::make('status')
                                                       ->label(__('red-jasmine-product::product-property.fields.status'))
                                                       ->inline()
                                                       ->required()
                                                       ->grouped()
                                                       ->default(PropertyStatusEnum::ENABLE)
                                                       ->useEnum(PropertyStatusEnum::class)
                         ,

                         ...static::operateFormSchemas()


                     ])
            ->columns(1);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')->label(__('red-jasmine-product::product-property.fields.id'))->copyable()->sortable(),
                          Tables\Columns\TextColumn::make('group.name')->label(__('red-jasmine-product::product-property.fields.group.name'))->numeric(),
                          Tables\Columns\TextColumn::make('type')->label(__('red-jasmine-product::product-property.fields.type'))
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('name')->label(__('red-jasmine-product::product-property.fields.name'))->searchable(),
                          Tables\Columns\TextColumn::make('unit')->label(__('red-jasmine-product::product-property.fields.unit'))
                          ,
                          Tables\Columns\IconColumn::make('is_required')->label(__('red-jasmine-product::product-property.fields.is_required'))->boolean(),
                          Tables\Columns\IconColumn::make('is_allow_multiple')->label(__('red-jasmine-product::product-property.fields.is_allow_multiple'))->boolean(),
                          Tables\Columns\IconColumn::make('is_allow_alias')->label(__('red-jasmine-product::product-property.fields.is_allow_alias'))->boolean(),
                          Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine-product::product-property.fields.sort'))->sortable(),
                          Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product-property.fields.status'))
                                                   ->useEnum(),
                          ...static::operateTableColumns()
                      ])
            ->filters([
                          Tables\Filters\SelectFilter::make('group_id')
                                                     ->label(__('red-jasmine-product::product-property-value.fields.group.name'))
                                                     ->relationship('group', 'name')
                                                     ->searchable()
                                                     ->optionsLimit(50)
                                                     ->preload(),
                          Tables\Filters\SelectFilter::make('status')
                                                     ->label(__('red-jasmine-product::product-property-value.fields.status'))
                                                     ->options(PropertyStatusEnum::options()),

                          Tables\Filters\TernaryFilter::make('is_required')
                              ->label(__('red-jasmine-product::product-property.fields.is_required'))
                                                      ->boolean(true),
                          Tables\Filters\TernaryFilter::make('is_allow_multiple')
                                                      ->label(__('red-jasmine-product::product-property.fields.is_allow_multiple'))
                                                      ->boolean(true),
                          Tables\Filters\TernaryFilter::make('is_allow_alias')
                                                      ->label(__('red-jasmine-product::product-property.fields.is_allow_alias'))
                                                      ->boolean(true),
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
