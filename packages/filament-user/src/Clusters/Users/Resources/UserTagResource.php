<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\RelationManagers;
use RedJasmine\User\Application\Services\UserGroupApplicationService;
use RedJasmine\User\Application\Services\UserTagApplicationService;
use RedJasmine\User\Domain\Data\UserGroupData;
use RedJasmine\User\Domain\Models\UserTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserTagResource extends Resource
{


    use ResourcePageHelper;

    public static string $service = UserTagApplicationService::class;
    public static string $dataClass = UserTag::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user-tag.labels.title');
    }
    protected static ?string $model = UserTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Users::class;
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->maxLength(255),
                Forms\Components\TextInput::make('color')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cluster')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(32)
                    ->default('enable'),
                Forms\Components\TextInput::make('extra'),
                Forms\Components\TextInput::make('version')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('creator_type')
                    ->maxLength(64),
                Forms\Components\TextInput::make('creator_id')
                    ->maxLength(64),
                Forms\Components\TextInput::make('updater_type')
                    ->maxLength(64),
                Forms\Components\TextInput::make('updater_id')
                    ->maxLength(64),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cluster')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort')
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
            'index' => Pages\ListUserTags::route('/'),
            'create' => Pages\CreateUserTag::route('/create'),
            'edit' => Pages\EditUserTag::route('/{record}/edit'),
        ];
    }
}
