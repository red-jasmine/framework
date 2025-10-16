<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\Pages\ListWalletTransactions;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\Pages\CreateWalletTransaction;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\Pages\EditWalletTransaction;
use RedJasmine\FilamentWallet\Clusters\Wallet;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\Pages;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\RelationManagers;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Wallet::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('transaction_no')
                    ->required()
                    ->maxLength(64),
                TextInput::make('wallet_id')
                    ->required()
                    ->numeric(),
                TextInput::make('wallet_type')
                    ->required()
                    ->maxLength(255),
                TextInput::make('direction')
                    ->required()
                    ->maxLength(255),
                TextInput::make('amount_currency')
                    ->required()
                    ->maxLength(3),
                TextInput::make('amount_total')
                    ->required()
                    ->numeric(),
                TextInput::make('balance')
                    ->required()
                    ->numeric(),
                TextInput::make('freeze')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('success'),
                DateTimePicker::make('trade_time')
                    ->required(),
                TextInput::make('app_id')
                    ->required()
                    ->maxLength(64),
                TextInput::make('transaction_type')
                    ->required()
                    ->maxLength(32),
                TextInput::make('title')
                    ->maxLength(64),
                TextInput::make('description')
                    ->maxLength(255),
                TextInput::make('bill_type')
                    ->maxLength(30),
                TextInput::make('out_trade_no')
                    ->maxLength(255),
                TextInput::make('tags')
                    ->maxLength(255),
                TextInput::make('remarks')
                    ->maxLength(255),
                TextInput::make('extra'),
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
                TextColumn::make('transaction_no')
                    ->searchable(),
                TextColumn::make('wallet_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wallet_type')
                    ->searchable(),
                TextColumn::make('direction')
                    ->searchable(),
                TextColumn::make('amount_currency')
                    ->searchable(),
                TextColumn::make('amount_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('freeze')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('trade_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('app_id')
                    ->searchable(),
                TextColumn::make('transaction_type')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('bill_type')
                    ->searchable(),
                TextColumn::make('out_trade_no')
                    ->searchable(),
                TextColumn::make('tags')
                    ->searchable(),
                TextColumn::make('remarks')
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
            'index' => ListWalletTransactions::route('/'),
            'create' => CreateWalletTransaction::route('/create'),
            'edit' => EditWalletTransaction::route('/{record}/edit'),
        ];
    }
}
