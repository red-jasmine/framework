<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use RedJasmine\Card\Application\Services\CardApplicationService;
use RedJasmine\Card\Application\UserCases\Command\CardCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\CardDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\CardUpdateCommand;
use RedJasmine\Card\Domain\Enums\CardStatus;
use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\FilamentCard\Clusters\Cards;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\Pages\CreateCard;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\Pages\EditCard;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\Pages\ListCards;
use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\Schemas\Operators;
use RedJasmine\FilamentCore\Resources\Schemas\Owner;

class CardResource extends Resource
{
    use ResourcePageHelper;

    protected static ?string $service        = CardApplicationService::class;
    protected static ?string $createCommand  = CardCreateCommand::class;
    protected static ?string $updateCommand  = CardUpdateCommand::class;
    protected static ?string $deleteCommand  = CardDeleteCommand::class;
    protected static ?string $model          = Card::class;
    protected static bool    $onlyOwner      = true;
    protected static ?int    $navigationSort = 2;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-ticket';

    public static function getModelLabel() : string
    {
        return __('red-jasmine-card::card.labels.card');
    }


    protected static ?string $cluster = Cards::class;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([

                Owner::make(),
                Flex::make([

                    Section::make([

                        Select::make('group_id')
                                               ->label(__('red-jasmine-card::card.fields.group_id'))
                                               ->relationship('group', 'name',
                                                   modifyQueryUsing: static::ownerQueryUsing()
                                               )
                                               ->required()
                                               ->default(0),

                        Textarea::make('content')
                                                 ->label(__('red-jasmine-card::card.fields.content'))
                                                 ->required()
                                                 ->columnSpanFull(),


                    ]),
                    Section::make([
                        Toggle::make('is_loop')
                                               ->label(__('red-jasmine-card::card.fields.is_loop'))
                                               ->required(),
                        ToggleButtons::make('status')
                                                      ->label(__('red-jasmine-card::card.fields.status'))
                                                      ->required()
                                                      ->grouped()
                                                      ->default(CardStatus::ENABLE)
                                                      ->useEnum(CardStatus::class),
                        DateTimePicker::make('sold_time')
                                                       ->label(__('red-jasmine-card::card.fields.sold_time'))
                                                       ->disabled(),

                        TextInput::make('remarks')
                                                  ->label(__('red-jasmine-card::card.fields.remarks'))
                                                  ->maxLength(255),


                    ])->grow(false),
                ])->columnSpanFull(),


                Operators::make(),
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
                TextColumn::make('group.name')
                                         ->label(__('red-jasmine-card::card.fields.group_id'))
                                         ->numeric()
                                         ->sortable(),
                IconColumn::make('is_loop')
                                         ->label(__('red-jasmine-card::card.fields.is_loop'))
                                         ->boolean(),
                TextColumn::make('status')
                                         ->label(__('red-jasmine-card::card.fields.status'))
                                         ->useEnum(),
                TextColumn::make('sold_time')
                                         ->label(__('red-jasmine-card::card.fields.sold_time'))
                                         ->dateTime()
                                         ->sortable(),
                TextColumn::make('remarks')
                                         ->label(__('red-jasmine-card::card.fields.remarks'))
                                         ->searchable(),
                
            ])
            ->filters([
                TrashedFilter::make(),
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
            'index'  => ListCards::route('/'),
            'create' => CreateCard::route('/create'),
            'edit'   => EditCard::route('/{record}/edit'),
        ];
    }

}
