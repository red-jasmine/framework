<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources;

use RedJasmine\Card\Application\Services\CardGroupCommandService;
use RedJasmine\Card\Application\Services\CardGroupQueryService;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupUpdateCommand;
use RedJasmine\FilamentCard\Clusters\Cards;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\Pages;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\RelationManagers;
use RedJasmine\Card\Domain\Models\CardGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;


class CardGroupResource extends Resource
{
    use ResourcePageHelper;

    protected static ?string $commandService = CardGroupCommandService::class;
    protected static ?string $queryService   = CardGroupQueryService::class;
    protected static ?string $createCommand  = CardGroupCreateCommand::class;
    protected static ?string $updateCommand  = CardGroupUpdateCommand::class;
    protected static ?string $deleteCommand  = CardGroupDeleteCommand::class;


    protected static ?string $model          = CardGroup::class;
    protected static bool    $onlyOwner      = true;
    protected static ?int    $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function getModelLabel() : string
    {
        return __('red-jasmine-card::card-group.labels.card-group');
    }


    protected static ?string $cluster = Cards::class;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         ...static::ownerFormSchemas(),
                         Forms\Components\TextInput::make('name')
                                                   ->label(__('red-jasmine-card::card-group.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('remarks')
                                                   ->label(__('red-jasmine-card::card-group.fields.remarks'))
                                                   ->maxLength(255),
                         ...static::operateFormSchemas(),
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
                          Tables\Columns\TextColumn::make('name')
                                                   ->label(__('red-jasmine-card::card-group.fields.name'))
                                                   ->searchable(),

                          Tables\Columns\TextColumn::make('cards_count')
                                                   ->badge()
                                                   ->label(__('red-jasmine-card::card.enums.status.enable').__('red-jasmine-card::card-group.labels.cards_count'))
                                                   ->counts([ 'cards' => fn(Builder $query) => $query->enable() ])
                          ,
                          Tables\Columns\TextColumn::make('products_count')
                                                   ->label(__('red-jasmine-card::card-group.labels.products_counts'))
                                                   ->badge()
                                                   ->counts('products')
                          ,
                          Tables\Columns\TextColumn::make('remarks')
                                                   ->label(__('red-jasmine-card::card-group.fields.remarks'))
                                                   ->searchable(),
                          ...static::operateTableColumns(),
                      ])
            ->filters([
                          // Tables\Filters\TrashedFilter::make(),
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
            'index'  => Pages\ListCardGroups::route('/'),
            'create' => Pages\CreateCardGroup::route('/create'),
            'edit'   => Pages\EditCardGroup::route('/{record}/edit'),
        ];
    }

}
