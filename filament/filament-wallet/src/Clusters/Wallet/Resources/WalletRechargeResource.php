<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\Pages\ListWalletRecharges;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\Pages\CreateWalletRecharge;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\Pages\EditWalletRecharge;
use RedJasmine\FilamentWallet\Clusters\Wallet;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\Pages;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\RelationManagers;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletRechargeResource extends Resource
{
    protected static ?string $model = WalletRecharge::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Wallet::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('recharge_no')
                    ->required()
                    ->maxLength(64),
                Select::make('wallet_id')
                    ->relationship('wallet', 'id')
                    ->required(),
                TextInput::make('wallet_type')
                    ->required()
                    ->maxLength(255),
                TextInput::make('owner_type')
                    ->required()
                    ->maxLength(32),
                TextInput::make('owner_id')
                    ->required()
                    ->maxLength(64),
                TextInput::make('amount_currency')
                    ->required()
                    ->maxLength(3),
                TextInput::make('amount_total')
                    ->required()
                    ->numeric(),
                TextInput::make('fee')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                TextInput::make('pay_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                TextInput::make('payment_type')
                    ->maxLength(255),
                TextInput::make('payment_id')
                    ->numeric(),
                TextInput::make('payment_channel_trade_no')
                    ->maxLength(64),
                TextInput::make('payment_mode')
                    ->maxLength(32),
                DateTimePicker::make('payment_time'),
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
                TextColumn::make('recharge_no')
                    ->searchable(),
                TextColumn::make('wallet.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wallet_type')
                    ->searchable(),
                TextColumn::make('owner_type')
                    ->searchable(),
                TextColumn::make('owner_id')
                    ->searchable(),
                TextColumn::make('amount_currency')
                    ->searchable(),
                TextColumn::make('amount_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('pay_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_type')
                    ->searchable(),
                TextColumn::make('payment_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_channel_trade_no')
                    ->searchable(),
                TextColumn::make('payment_mode')
                    ->searchable(),
                TextColumn::make('payment_time')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ListWalletRecharges::route('/'),
            'create' => CreateWalletRecharge::route('/create'),
            'edit' => EditWalletRecharge::route('/{record}/edit'),
        ];
    }
}
