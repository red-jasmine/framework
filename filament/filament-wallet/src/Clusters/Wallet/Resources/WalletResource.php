<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\Pages\ListWallets;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\Pages\CreateWallet;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\Pages\EditWallet;
use RedJasmine\FilamentWallet\Clusters\Wallet as Cluster;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\Pages;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\RelationManagers;
use RedJasmine\Wallet\Domain\Models\Wallet;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Cluster::class;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required()
                    ->maxLength(32),
                TextInput::make('owner_type')
                    ->required()
                    ->maxLength(32),
                TextInput::make('owner_id')
                    ->required()
                    ->maxLength(64),
                TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default('CNY'),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                TextInput::make('freeze')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                TextInput::make('status')
                    ->required()
                    ->maxLength(32)
                    ->default('enable'),
                TextInput::make('version')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('creator_type')
                    ->maxLength(32),
                TextInput::make('creator_id')
                    ->maxLength(64),
                TextInput::make('updater_type')
                    ->maxLength(32),
                TextInput::make('updater_id')
                    ->maxLength(64),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('owner_type')
                    ->searchable(),
                TextColumn::make('owner_id')
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('balance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('freeze')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('version')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('creator_type')
                    ->searchable(),
                TextColumn::make('creator_id')
                    ->searchable(),
                TextColumn::make('updater_type')
                    ->searchable(),
                TextColumn::make('updater_id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWallets::route('/'),
            'create' => CreateWallet::route('/create'),
            'edit' => EditWallet::route('/{record}/edit'),
        ];
    }
}
