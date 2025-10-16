<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\Pages\ListCardGroups;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\Pages\CreateCardGroup;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\Pages\EditCardGroup;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Card\Application\Services\CardGroupApplicationService;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupUpdateCommand;
use RedJasmine\Card\Domain\Models\CardGroup;
use RedJasmine\FilamentCard\Clusters\Cards;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\Pages;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;


class CardGroupResource extends Resource
{
    use ResourcePageHelper;

    protected static ?string $service = CardGroupApplicationService::class;

    protected static ?string $createCommand = CardGroupCreateCommand::class;
    protected static ?string $updateCommand = CardGroupUpdateCommand::class;
    protected static ?string $deleteCommand = CardGroupDeleteCommand::class;


    protected static ?string $model          = CardGroup::class;
    protected static bool    $onlyOwner      = true;
    protected static ?int    $navigationSort = 1;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-group';

    public static function getModelLabel() : string
    {
        return __('red-jasmine-card::card-group.labels.card-group');
    }


    protected static ?string $cluster = Cards::class;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                ...static::ownerFormSchemas(),
                TextInput::make('name')
                                          ->label(__('red-jasmine-card::card-group.fields.name'))
                                          ->required()
                                          ->maxLength(255),
                TextInput::make('remarks')
                                          ->label(__('red-jasmine-card::card-group.fields.remarks'))
                                          ->maxLength(255),
                ...static::operateFormSchemas(),
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
                TextColumn::make('name')
                                         ->label(__('red-jasmine-card::card-group.fields.name'))
                                         ->searchable(),

                TextColumn::make('cards_count')
                                         ->badge()
                                         ->label(__('red-jasmine-card::card.enums.status.enable').__('red-jasmine-card::card-group.labels.cards_count'))
                                         ->counts(['cards' => fn(Builder $query) => $query->enable()])
                ,
                TextColumn::make('products_count')
                                         ->label(__('red-jasmine-card::card-group.labels.products_counts'))
                                         ->badge()
                                         ->counts('products')
                ,
                TextColumn::make('remarks')
                                         ->label(__('red-jasmine-card::card-group.fields.remarks'))
                                         ->searchable(),
                ...static::operateTableColumns(),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
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
            'index'  => ListCardGroups::route('/'),
            'create' => CreateCardGroup::route('/create'),
            'edit'   => EditCardGroup::route('/{record}/edit'),
        ];
    }

}
