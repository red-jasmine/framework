<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources;

use RedJasmine\FilamentWallet\Clusters\Wallet;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\Pages;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\RelationManagers;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletRechargeResource extends Resource
{
    protected static ?string $model = WalletRecharge::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Wallet::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('recharge_no')
                    ->required()
                    ->maxLength(64),
                Forms\Components\Select::make('wallet_id')
                    ->relationship('wallet', 'id')
                    ->required(),
                Forms\Components\TextInput::make('wallet_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('owner_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('owner_id')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('amount_currency')
                    ->required()
                    ->maxLength(3),
                Forms\Components\TextInput::make('amount_total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('fee')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pay_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('payment_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_id')
                    ->numeric(),
                Forms\Components\TextInput::make('payment_channel_trade_no')
                    ->maxLength(64),
                Forms\Components\TextInput::make('payment_mode')
                    ->maxLength(32),
                Forms\Components\DateTimePicker::make('payment_time'),
                Forms\Components\TextInput::make('extra'),
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
                Tables\Columns\TextColumn::make('recharge_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wallet.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wallet_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fee')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pay_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_channel_trade_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_mode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_time')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListWalletRecharges::route('/'),
            'create' => Pages\CreateWalletRecharge::route('/create'),
            'edit' => Pages\EditWalletRecharge::route('/{record}/edit'),
        ];
    }
}
