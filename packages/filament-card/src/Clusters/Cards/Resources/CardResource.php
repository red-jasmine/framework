<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources;

use RedJasmine\Card\Application\Services\CardCommandService;
use RedJasmine\Card\Application\Services\CardQueryService;
use RedJasmine\Card\Application\UserCases\Command\CardCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\CardDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\CardUpdateCommand;
use RedJasmine\Card\Domain\Enums\CardStatus;
use RedJasmine\FilamentCard\Clusters\Cards;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\Pages;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\RelationManagers;
use RedJasmine\Card\Domain\Models\Card;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CardResource extends Resource
{
    use ResourcePageHelper;

    protected static ?string $commandService = CardCommandService::class;
    protected static ?string $queryService   = CardQueryService::class;
    protected static ?string $createCommand  = CardCreateCommand::class;
    protected static ?string $updateCommand  = CardUpdateCommand::class;
    protected static ?string $deleteCommand  = CardDeleteCommand::class;
    protected static ?string $model          = Card::class;
    protected static bool    $onlyOwner      = true;
    protected static ?int    $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function getModelLabel() : string
    {
        return __('red-jasmine-card::card.labels.card');
    }


    protected static ?string $cluster = Cards::class;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([

                         ...static::ownerFormSchemas(),
                         Forms\Components\Select::make('group_id')
                                                ->label(__('red-jasmine-card::card.fields.group_id'))
                                                ->relationship('group', 'name',
                                                    modifyQueryUsing: static::ownerQueryUsing()
                                                )
                                                ->required()
                                                ->default(0),
                         Forms\Components\Toggle::make('is_loop')
                                                ->label(__('red-jasmine-card::card.fields.is_loop'))
                                                ->required(),
                         Forms\Components\ToggleButtons::make('status')
                                                       ->label(__('red-jasmine-card::card.fields.status'))
                                                       ->required()
                                                       ->grouped()
                                                       ->default(CardStatus::ENABLE)
                                                       ->useEnum(CardStatus::class),
                         Forms\Components\DateTimePicker::make('sold_time')
                                                        ->label(__('red-jasmine-card::card.fields.sold_time'))
                                                        ->disabled(),
                         Forms\Components\Textarea::make('content')
                                                  ->label(__('red-jasmine-card::card.fields.content'))
                                                  ->required()
                                                  ->columnSpanFull(),
                         Forms\Components\TextInput::make('remarks')
                                                   ->label(__('red-jasmine-card::card.fields.remarks'))
                                                   ->maxLength(255),
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
                          Tables\Columns\TextColumn::make('group.name')
                                                   ->label(__('red-jasmine-card::card.fields.group_id'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\IconColumn::make('is_loop')
                                                   ->label(__('red-jasmine-card::card.fields.is_loop'))
                                                   ->boolean(),
                          Tables\Columns\TextColumn::make('status')
                                                   ->label(__('red-jasmine-card::card.fields.status'))
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('sold_time')
                                                   ->label(__('red-jasmine-card::card.fields.sold_time'))
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('remarks')
                                                   ->label(__('red-jasmine-card::card.fields.remarks'))
                                                   ->searchable(),
                          ...static::operateTableColumns(),
                      ])
            ->filters([
                          Tables\Filters\TrashedFilter::make(),
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
            'index'  => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit'   => Pages\EditCard::route('/{record}/edit'),
        ];
    }

}
