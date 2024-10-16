<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RedJasmine\Card\Application\Services\CardGroupBindProductCommandService;
use RedJasmine\Card\Application\Services\CardGroupBindProductQueryService;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductUpdateCommand;
use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\FilamentCard\Clusters\Cards;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CardGroupBindProductResource extends Resource
{


    use ResourcePageHelper;

    protected static ?int $navigationSort = 3;

    protected static ?string $commandService = CardGroupBindProductCommandService::class;
    protected static ?string $queryService   = CardGroupBindProductQueryService::class;
    protected static ?string $createCommand  = CardGroupBindProductCreateCommand::class;
    protected static ?string $updateCommand  = CardGroupBindProductUpdateCommand::class;
    protected static ?string $deleteCommand  = CardGroupBindProductDeleteCommand::class;
    protected static ?string $model          = CardGroupBindProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $cluster = Cards::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-card::card-group-bind-product.labels.card-group-bind-product');
    }

    public static function callResolveRecord(Model $model) : Model
    {

        return $model;
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->columns(1)
            ->schema([
                         ...static::ownerFormSchemas(),
                         Forms\Components\Select::make('group_id')
                                                ->label(__('red-jasmine-card::card-group-bind-product.fields.group_id'))
                                                ->relationship('group', 'name')
                                                ->required(),
                         Forms\Components\MorphToSelect::make('product')
                                                       ->label(__('red-jasmine-card::card-group-bind-product.fields.product'))
                                                       ->types([
                                                                   ...collect(static::$model::$morphLabels)
                                                                       ->map(fn($label, $item) => Forms\Components\MorphToSelect\Type::make($item)
                                                                                                                                     ->titleAttribute('title')
                                                                                                                                     ->label($label)),

                                                               ])

                         ,
                         Forms\Components\TextInput::make('sku_id')
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
                          Tables\Columns\TextColumn::make('id')
                                                   ->label('ID')
                                                   ->sortable(),
                          ...static::ownerTableColumns(),
                          Tables\Columns\TextColumn::make('product.title')
                                                   ->label(__('red-jasmine-card::card-group-bind-product.fields.product'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('product_id')
                                                   ->label(__('red-jasmine-card::card-group-bind-product.fields.product_id'))
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('sku_id')
                                                   ->label(__('red-jasmine-card::card-group-bind-product.fields.sku_id'))
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('group.name')
                                                   ->label(__('red-jasmine-card::card-group-bind-product.fields.group_id'))
                                                   ->numeric()
                                                   ->sortable(),
                          ...static::operateTableColumns()
                      ])
            ->filters([
                          //Tables\Filters\TrashedFilter::make(),
                      ])
            ->actions([
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
            'index'  => Pages\ListCardGroupBindProducts::route('/'),
            'create' => Pages\CreateCardGroupBindProduct::route('/create'),
            'edit'   => Pages\EditCardGroupBindProduct::route('/{record}/edit'),
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
