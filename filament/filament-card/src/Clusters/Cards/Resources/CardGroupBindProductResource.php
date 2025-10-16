<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages\ListCardGroupBindProducts;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages\CreateCardGroupBindProduct;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages\EditCardGroupBindProduct;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Card\Application\Services\CardGroupBindProductApplicationService;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductUpdateCommand;
use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\FilamentCard\Clusters\Cards;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\Support\Data\UserData;

class CardGroupBindProductResource extends Resource
{


    use ResourcePageHelper;

    protected static ?int $navigationSort = 3;

    protected static ?string $service = CardGroupBindProductApplicationService::class;
    protected static ?string $createCommand = CardGroupBindProductCreateCommand::class;
    protected static ?string $updateCommand = CardGroupBindProductUpdateCommand::class;
    protected static ?string $deleteCommand = CardGroupBindProductDeleteCommand::class;
    protected static ?string $model         = CardGroupBindProduct::class;


    protected static bool $onlyOwner = true;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-link';

    protected static ?string $cluster = Cards::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-card::card-group-bind-product.labels.card-group-bind-product');
    }

    public static function callResolveRecord(Model $model) : Model
    {

        return $model;
    }

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                ...static::ownerFormSchemas(),
                Select::make('group_id')
                                       ->label(__('red-jasmine-card::card-group-bind-product.fields.group_id'))
                                       ->relationship('group', 'name',
                                           modifyQueryUsing: fn(
                                               Builder $query,
                                               Get $get
                                           ) => $query->onlyOwner(UserData::from(['type' => $get('owner_type'), 'id' => $get('owner_id')]))
                                       )
                                       ->searchable()
                                       ->preload()
                                       ->optionsLimit(30)
                                       ->required(),
                MorphToSelect::make('product')
                                              ->label(__('red-jasmine-card::card-group-bind-product.fields.product'))
                                              ->types([
                                                  ...collect(static::$model::$morphLabels)
                                                      ->map(fn($label, $item) => Type::make($item)
                                                                                                                    ->titleAttribute('title')
                                                                                                                    ->modifyOptionsQueryUsing(fn(
                                                                                                                        Builder $query,
                                                                                                                        Get $get
                                                                                                                    ) => $query->onlyOwner(UserData::from([
                                                                                                                        'type' => $get('owner_type'),
                                                                                                                        'id'   => $get('owner_id')
                                                                                                                    ])))
                                                                                                                    ->label($label)),

                                              ])

                ,
                TextInput::make('sku_id')
                                          ->label(__('red-jasmine-card::card-group-bind-product.fields.sku_id'))
                                          ->numeric()
                                          ->default(0),
                ...static::operateFormSchemas()
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                                         ->label('ID')
                                         ->sortable(),
                ...static::ownerTableColumns(),
                TextColumn::make('product.title')
                                         ->label(__('red-jasmine-card::card-group-bind-product.fields.product'))
                                         ->searchable(),
                TextColumn::make('product_id')
                                         ->label(__('red-jasmine-card::card-group-bind-product.fields.product_id'))
                ,
                TextColumn::make('sku_id')
                                         ->label(__('red-jasmine-card::card-group-bind-product.fields.sku_id'))
                                         ->copyable(),
                TextColumn::make('group.name')
                                         ->label(__('red-jasmine-card::card-group-bind-product.fields.group_id'))
                                         ->numeric()
                                         ->sortable(),
                ...static::operateTableColumns()
            ])
            ->filters([
                //Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => ListCardGroupBindProducts::route('/'),
            'create' => CreateCardGroupBindProduct::route('/create'),
            'edit' => EditCardGroupBindProduct::route('/{record}/edit'),
        ];
    }

}
