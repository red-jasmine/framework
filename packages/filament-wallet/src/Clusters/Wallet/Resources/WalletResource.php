<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources;

use RedJasmine\FilamentWallet\Clusters\Wallet as Cluster;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\Pages;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\RelationManagers;
use RedJasmine\Wallet\Domain\Models\Wallet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Cluster::class;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('owner_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('owner_id')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default('CNY'),
                Forms\Components\TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('freeze')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(32)
                    ->default('enable'),
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
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('balance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('freeze')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
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
            'index' => Pages\ListWallets::route('/'),
            'create' => Pages\CreateWallet::route('/create'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
        ];
    }
}
