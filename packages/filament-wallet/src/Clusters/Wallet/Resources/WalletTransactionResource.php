<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources;

use RedJasmine\FilamentWallet\Clusters\Wallet;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\Pages;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\RelationManagers;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Wallet::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('transaction_no')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('wallet_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('wallet_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('direction')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount_currency')
                    ->required()
                    ->maxLength(3),
                Forms\Components\TextInput::make('amount_total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('balance')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('freeze')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('success'),
                Forms\Components\DateTimePicker::make('trade_time')
                    ->required(),
                Forms\Components\TextInput::make('app_id')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('transaction_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('title')
                    ->maxLength(64),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('bill_type')
                    ->maxLength(30),
                Forms\Components\TextInput::make('out_trade_no')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tags')
                    ->maxLength(255),
                Forms\Components\TextInput::make('remarks')
                    ->maxLength(255),
                Forms\Components\TextInput::make('extras'),
                Forms\Components\TextInput::make('version')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('creator_type')
                    ->maxLength(32),
                Forms\Components\TextInput::make('creator_id')
                    ->maxLength(64),
                Forms\Components\TextInput::make('updater_type')
                    ->maxLength(32),
                Forms\Components\TextInput::make('updater_id')
                    ->maxLength(64),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wallet_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wallet_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direction')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('freeze')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('trade_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('app_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bill_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('out_trade_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags')
                    ->searchable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable(),
                Tables\Columns\TextColumn::make('version')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('creator_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updater_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updater_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListWalletTransactions::route('/'),
            'create' => Pages\CreateWalletTransaction::route('/create'),
            'edit' => Pages\EditWalletTransaction::route('/{record}/edit'),
        ];
    }
}
